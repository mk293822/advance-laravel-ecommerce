<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(): \Inertia\Response
    {
      $products = Product::query()->forWebsite()->paginate(10);
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
}
