<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Traits\CrudTrait;
use App\Utils\CrudConfig;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->init(new CrudConfig(
            resource: 'products',
            modelClass: Product::class,
            storeRequestClass: ProductStoreRequest::class,
            updateRequestClass: ProductUpdateRequest::class,
            componentPath: 'Products/Index',
            searchColumns: ['name', 'description'],
            exportClass: ProductExport::class,
            withRelations: ['category:id,name', 'brand:id,name', 'subCategory:id,name', 'tags:id,name'],
            addProps: $this->addProps(),
        ));
    }

    protected function addProps(): array
    {
        return [
            'categories' => Category::select('id', 'name')->get(),
            'brands' => Brand::select('id', 'name')->get(),
            'subCategories' => SubCategory::select('id', 'name')->get(),
            'tags' => Tag::select('id', 'name')->get(),
        ];
    }

    public function indexDisabledForNow(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');

        $query = Product::query();
        $query->with(['category:id,name', 'brand:id,name', 'subCategory:id,name', 'tags:id,name']);

        $searchColumns = ['name', 'slug'];

        $query->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                foreach ($searchColumns as $column) {
                    $query->orWhere($column, 'like', "%{$search}%")
                        ->orWhere();
                }
            });
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
            ...$this->addProps(),
        ]);
    }
}
