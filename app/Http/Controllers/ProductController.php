<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeaprtmentResourece;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Department;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
  public function index(Request $request): \Inertia\Response
  {
    $keyword = $request->query('keyword');
    $products = Product::query()
      ->forWebsite()
      ->when($keyword, function ($query, $keyword) {
        $query->where(function ($query) use ($keyword) {
          $query->where('title', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%");
        });
      })->paginate(10);

    return Inertia::render('Home', [
      'products' => ProductListResource::collection($products),
    ]);
  }

  public function show(Product $product): \Inertia\Response
  {
    return Inertia::render('Product/Show', [
      'product' => new ProductResource($product),
      'variationOptions' => request('options', [])
    ]);
  }

  public function byDepartment(Request $request, Department $department)
  {
    abort_unless($department->active, 404);

    $keyword = $request->query('keyword');
    $product = Product::query()
      ->forWebsite()
      ->where('department_id', $department->id)
      ->when($keyword, function ($query, $keyword) {
        $query->where(function ($query) use ($keyword) {
          $query->where('title', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%");
        });
      })->paginate();

    return Inertia::render('Department/Index', [
      'department' => new DeaprtmentResourece($department),
      'products' => ProductListResource::collection($product),

    ]);
  }
}