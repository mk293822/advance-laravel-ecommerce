<?php

namespace App\Http\Controllers;

use App\Enums\RolesEnum;
use App\Enums\VendorEnum;
use App\Http\Resources\ProductListResource;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VendorController extends Controller
{
  public function profile(Vendor $vendor, Request $request)
  {
    $keyword = $request->query('keyword');
    $product = Product::query()
      ->forWebsite()
      ->when($keyword, function($query, $keyword) {
        $query->where(function ($query) use ($keyword) {
          $query->where('title', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%");
        });
      })
      ->where('created_by', $vendor->user_id)
      ->paginate(10);

    return Inertia::render('Vendor/Profile', [
      'vendor' => $vendor,
      'products' => ProductListResource::collection($product),
    ]);
  }

  public function store(Request $request)
  {
    $user = $request->user();

    $request->validate([
      'store_name'=>[
        'required',
        'regex:/^[a-z0-9-]+$/',
        \Illuminate\Validation\Rule::unique('vendors', 'store_name')
          ->ignore($user->id, 'user_id')
        ],
      'store_address'=>'nullable',
    ], [
      'store_name.regex' => 'Store name must only contain lowercase alphanumeric characters and dashes.',
    ]);

    $vendor = $user->vendor?:new Vendor();
    $vendor->user_id = $user->id;
    $vendor->status = VendorEnum::Approved->value;
    $vendor->store_name = $request->store_name;
    $vendor->store_address = $request->status_address;
    $vendor->save();

    $user->assignRole(RolesEnum::Vendor);
  }
}
