<?php

namespace App\Traits;

use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

trait CrudTrait
{
    public string $resource;

    public string $modelClass;

    public string $storeRequestClass;

    public string $updateRequestClass;

    public array $searchColumns;

    public string $exportClass;

    public string $componentPath;

    public array $withRelations = [];


    public function init(array $config): void
    {
        $this->resource = $config['resource'] ?? $this->resource;
        $this->modelClass = $config['modelClass'] ?? $this->modelClass;
        $this->storeRequestClass = $config['storeRequestClass'] ?? $this->storeRequestClass;
        $this->updateRequestClass = $config['updateRequestClass'] ?? $this->updateRequestClass;
        $this->searchColumns = $config['searchColumns'] ?? $this->searchColumns;
        $this->exportClass = $config['exportClass'] ?? $this->exportClass;
        $this->componentPath = $config['componentPath'] ?? $this->componentPath;
        $this->withRelations = $config['withRelations'] ?? $this->withRelations;
        $this->addProps = $config['addProps'] ?? $this->addProps();
    }


    public function index(Request $request)
    {
        $this->logThisMethod();
        $this->ensureModelClass();

        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');

        $query =  $this->modelClass::query();

        if (!empty($this->withRelations)) {
            $query->with($this->withRelations);
        }

        $query->when($search, function ($query, $search) {
            if (isset($this->searchColumns) && !empty($this->searchColumns)) {
                $query->where(function ($query) use ($search) {
                    foreach ($this->searchColumns as $column) {
                        $query->orWhere($column, 'like', "%{$search}%");
                    }
                });
            }
        });

        if ($request->has('trashed')) {
            $query->when($request->trashed, fn($query) => $query->onlyTrashed());
        }

        $query = $this->modifyQuery($query);
        $items = $query->latest()->paginate($perPage);

        return Inertia::render($this->componentPath, [
            'items' => $items,
            'filters' => ['search' => $search],
            'config' => $this->makeConfig(),
            ... $this->addProps(),
        ]);
    }

    protected function addProps() : array
    {
        return [];
    }

    protected function modifyQuery($query)
    {
        return $query;
    }

    private function makeConfig()
    {
        $modelRawName = class_basename($this->modelClass);
        $modelLowerCase = Str::snake($modelRawName);

        $routes = [
            'indexRoute' => $this->resource . '.index',
            'indexRouteTrashed' => $this->resource . '.index',
            'storeRoute' => $this->resource . '.store',
            'updateRoute' => $this->resource . '.update',
            'deleteRoute' => $this->resource . '.destroy',
            'bulkDeleteRoute' => $this->resource . '.bulk-destroy',
            'bulkRestoreRoute' => $this->resource . '.bulk-restore',
            'bulkForceDeleteRoute' => $this->resource . '.bulk-force-delete',
            'exportRoute' => $this->resource . '.export',
        ];

        $config = [
            'title' => Str::title($this->resource),
            'modelSingular' => $modelLowerCase,
            'modelRaw' => $modelRawName,
            'resource' => $this->resource,
        ];

        foreach ($routes as $key => $route) {
            if ($key === 'indexRouteTrashed' && Route::has($route)) {
                $config[$key] = route($route, ['trashed' => true]);
            } elseif (in_array($key, ['updateRoute', 'deleteRoute']) && Route::has($route)) {
                $config[$key] = route($route, [$modelLowerCase => '__ID__']);
            } else {
                $config[$key] = Route::has($route) ? route($route) : '#';
            }
        }
        return $config;
    }

    public function create()
    {
        $this->logThisMethod();

        return Inertia::render($this->componentPath);
    }

    public function store(Request $request)
    {
        $this->logThisMethod();
        $this->ensureModelClass();
        $validatedData = app($this->storeRequestClass)->validated();
        if ($request->file('photo')) {
            $validatedData['photo'] = $request->file('photo')->store($this->resource);
        }
        $model = new $this->modelClass;
        $model->fill($validatedData);
        $model->save();
    }

    public function update(Request $request, $id)
    {
        $this->logThisMethod();
        $validatedData = app($this->updateRequestClass)->validated();
        Log::debug($validatedData);
        $model = $this->modelClass::findOrFail($id);
        if ($request->file('photo')) {
            $validatedData['photo'] = $request->file('photo')->store($this->resource);
            // Delete existing photo
            if ($model->photo && Storage::fileExists($model->photo)) {
                Storage::delete($model->photo);
            }
        }
        $res = $model->update($validatedData);

        return to_route($this->resource . '.index')->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        $this->logThisMethod();
        $model = $this->modelClass::findOrFail($id);
        // if ($model->photo && Storage::exists($model->photo)) {
        //     Storage::delete($model->photo);
        // }
        $model->delete();

        return to_route($this->resource . '.index')->with('success', 'Deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $this->logThisMethod();
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:' . $this->modelClass . ',id']);
        foreach ($request->ids as $id) {
            $model = $this->modelClass::find($id);
            if ($model) {
                $model->delete();
            }
        }

        return to_route($this->resource . '.index')->with('success', 'Items deleted successfully');
    }

    public function bulkRestore(Request $request)
    {
        $this->logThisMethod();
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:' . $this->modelClass . ',id']);
        $this->modelClass::whereIn('id', $request->ids)->restore();
    }

    public function bulkForceDelete(Request $request)
    {
        $this->logThisMethod();
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:' . $this->modelClass . ',id']);
        // $this->modelClass::whereIn('id', $request->ids)->forceDelete();
        foreach ($request->ids as $id) {
            $model = $this->modelClass::withTrashed()->find($id);
            if ($model) {
                if ($model->photo && Storage::exists($model->photo)) {
                    Storage::delete($model->photo);
                }
                $model->forceDelete();
            }
        }
    }

    public function export(Request $request)
    {
        $this->logThisMethod();
        $search = $request->input('search');

        $filename = strtolower(class_basename($this->modelClass)) . '-' . now()->format('Y-m-d-H-i-s') . '.xlsx';

        return Excel::download(new $this->exportClass($search), $filename);
    }

    protected function ensureModelClass()
    {
        if (! $this->modelClass) {
            throw new \Exception('Model class not defined in trait usage');
        }
    }

    public function logThisMethod()
    {
        $log = false;
        if ($log == true) {
            $backtrace = debug_backtrace();
            logger()->info(__TRAIT__ . ' method called from : ' . $backtrace[1]['class']);
        }
    }
}
