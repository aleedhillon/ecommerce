<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

trait CrudTrait
{
    public string $resource;

    public string $modelClass;

    public string $storeRequestClass;

    public string $updateRequestClass;

    public string $exportClass;

    public string $componentPath;

    public function index(Request $request)
    {
        $this->logThisMethod();
        $this->ensureModelClass();
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');

        $items = $this->modelClass::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%")
                    ->orWhere('updated_at', 'like', "%{$search}%");
            })
            ->when($request->trashed, fn ($query) => $query->onlyTrashed())
            ->latest()
            ->paginate($perPage);

        return Inertia::render($this->componentPath, [
            'items' => $items,
            'filters' => ['search' => $search],
            'config' => $this->makeConfig(),
        ]);
    }

    private function makeConfig()
    {
        $modelRawName = class_basename($this->modelClass);
        $modelLowerCase = Str::lower($modelRawName);
        $config = [
            'title' => Str::title($this->resource),
            'modelSingular' => $modelLowerCase,
            'modelRaw' => $modelRawName,
            'resource' => $this->resource,
            'indexRoute' => route($this->resource.'.index'),
            'indexRouteTrashed' => route($this->resource.'.index', ['trashed' => true]),
            'storeRoute' => route($this->resource.'.store'),
            'updateRoute' => route($this->resource.'.update', [$modelLowerCase => '__ID__']),
            'deleteRoute' => route($this->resource.'.destroy', [$modelLowerCase => '__ID__']),
            'bulkDeleteRoute' => route($this->resource.'.bulk-destroy'),
            'bulkRestoreRoute' => route($this->resource.'.bulk-restore'),
            'bulkForceDeleteRoute' => route($this->resource.'.bulk-force-delete'),
            'exportRoute' => route($this->resource.'.export'),
        ];

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

        return to_route($this->resource.'.index')->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        $this->logThisMethod();
        $model = $this->modelClass::findOrFail($id);
        // if ($model->photo && Storage::exists($model->photo)) {
        //     Storage::delete($model->photo);
        // }
        $model->delete();

        return to_route($this->resource.'.index')->with('success', 'Deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $this->logThisMethod();
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:'.$this->modelClass.',id']);
        foreach ($request->ids as $id) {
            $model = $this->modelClass::find($id);
            if ($model) {
                $model->delete();
            }
        }

        return to_route($this->resource.'.index')->with('success', 'Items deleted successfully');
    }

    public function bulkRestore(Request $request)
    {
        $this->logThisMethod();
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:'.$this->modelClass.',id']);
        $this->modelClass::whereIn('id', $request->ids)->restore();
    }

    public function bulkForceDelete(Request $request)
    {
        $this->logThisMethod();
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:'.$this->modelClass.',id']);
        // $this->modelClass::whereIn('id', $request->ids)->forceDelete();
        foreach ($request->ids as $id) {
            $model = $this->modelClass::find($id);
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

        $filename = strtolower(class_basename($this->modelClass)).'-'.now()->format('Y-m-d-H-i-s').'.xlsx';

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
            logger()->info(__TRAIT__.' method called from : '.$backtrace[1]['class']);
        }
    }
}
