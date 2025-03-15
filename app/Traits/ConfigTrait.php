<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

trait ConfigTrait
{
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
}
