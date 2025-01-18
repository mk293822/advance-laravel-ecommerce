<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CartService $cartService): object
    {
      return Inertia::render('Cart/Index', [
        'cartItems'=> $cartService->getCartItemGrouped(),
      ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product, CartService $cartService): object
    {
        $request->mergeIfMissing([
            "quantity" => 1,
        ]);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'option_ids' => ['nullable', 'array'],
        ]);

        $cartService->addItemToCart($product, $data['quantity'], $data['option_ids'] ?: []);

        return back()->with('success', 'Product Added to the Cart Successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartService $cartService): object
    {
        $request->mergeIfMissing([
            "quantity" => 1,
        ]);

        $option_ids = $request->input("option_ids") ?: [];
        $quantity = $request->input("quantity");
        $product_id = $request->input("product_id");

//        dd($quantity, $product_id, $option_ids);

        $cartService->updateItemQuantity($product_id, $quantity, $option_ids);

        return back()->with("success", "Quantity has been Updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CartService $cartService): object
    {
        $option_ids = $request->input("option_ids");
        $product_id = $request->product_id;
//        dd($product);

        $cartService->removeItemFromCart($product_id, $option_ids);

        return back()->with("success", "Product remove from Cart Successfully!");
    }

  public function checkout()
  {

    }
}
