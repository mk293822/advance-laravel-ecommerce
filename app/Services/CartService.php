<?php

namespace App\Services;

use App\Models\CartItems;
use App\Models\Product;
use App\Models\VariationTypeOption;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Nullable;
use Illuminate\Support\Str;

class CartService
{
    private ?array $cachedCartItems = null;

    protected const COOKIE_NAME = 'cartItems';

    protected const COOKIE_LIFETIME = 60 * 24 * 365;
    /**
     * Create a new class instance.
     */
    public function addItemToCart(Product $product, int $quantity = 1, $option_ids = null): void
    {
        if($option_ids === null){
          $option_ids = $product->variationTypes->mapWithKeys( fn(VariationTypeOption $type) => [$type->id => $type->options[0]->id])->toArray();
        }

        $price = $product->getPriceForOption($option_ids);

        if(Auth::check()){
          $this->saveItemToDatabase($product->id, $quantity, $price, $option_ids);
        } else {
          $this->saveItemToCookies($product->id, $quantity, $price, $option_ids);
        }
    }

    public function updateItemQuantity(int $product_id, int $quantity = 1, $option_ids = null): void
    {
        if (Auth::check()) {
          $this->updateItemQuantityInDatabase($product_id, $quantity, $option_ids);
        } else {
          $this->updateItemQuantityInCookies($product_id, $quantity, $option_ids);
        }
    }

    public function removeItemFromCart(int $product_id, $option_ids = null):void
    {
        if(Auth::check()){
          $this->removeItemFromDatabase($product_id, $option_ids);
        } else{
          $this->removeItemFromCookies($product_id, $option_ids);
        }
    }

    public function getCartItems(): array
    {
        try {
            if ($this->cachedCartItems === null) {
                if (Auth::check()) {
                    $cartItems = $this->getCartItemsFromDatabase();
                } else {
                    $cartItems = $this->getCartItemsFromCookies();
//                    dd($cartItems);
                }
                $product_ids = collect($cartItems)->map(fn($item) => $item['product_id']);
                $products = Product::whereIn('id', $product_ids)->with('user.vendor')->forWebsite()->get()->keyBy('id');


                $cartItemData = [];
//                dd($products);
                foreach ($cartItems as $key => $cartItem) {
                  $product = data_get($products, $cartItem['product_id']);
//                  dd($product);
                  if(!$product) continue;

//                  dd($cartItem['option_ids']);

                  $optionInfo = [];
                  $options = VariationTypeOption::with('variationType')->whereIn('id', $cartItem['option_ids'])->get()->keyBy('id');


                  $image_url = null;

                  foreach ($cartItem['option_ids'] as $option_id) {
                    $option = data_get($options, $option_id);
                    if(!$image_url){
                      $image_url = $option->getFirstMediaUrl('images', 'small');
                    }
                    $optionInfo[] = [
                      "id" => $option_id,
                      "name" => $option->name,
                      "type" => [
                        "id" => $option->variationType->id,
                        "name" => $option->variationType->name,
                      ]
                    ];
                  }

//                  dd($product->user);

                  $cartItemData[] = [
                    "id" => $cartItem['id'],
                    "product_id" => $product->id,
                    "title" => $product->title,
                    "slug" => $product->slug,
                    "price" => $cartItem['price'],
                    "quantity" => $cartItem['quantity'],
                    "option_ids" => $cartItem['option_ids'],
                    "options" => $optionInfo,
                    "image"=> $image_url ?: $product->getFirstMediaUrl('images', 'small'),
                    "user" => [
                      "id"=> $product->created_by,
                      "name"=> $product->user->vendor->store_name,
                    ]
                  ];

              }

                $this->cachedCartItems = $cartItemData;
            }

            return $this->cachedCartItems;
        } catch (\Exception $e) {
//          throw $e;
          Log::error($e->getMessage(). PHP_EOL . $e->getTraceAsString());
        }
        return [];
    }

    public function getTotalQuantity(): int
    {
        $totalQuantity = 0;
        foreach ($this->getCartItems() as $cartItem) {
          $totalQuantity += $cartItem['quantity'];
        }
        return $totalQuantity;
    }

    public function getTotalPrice(): float
    {
        $totalPrice = 0;
        foreach ($this->getCartItems() as $cartItem) {
          $totalPrice += $cartItem['price'] * $cartItem['quantity'];
        }
        return $totalPrice;
    }

    protected function updateItemQuantityInDatabase(int $product_id, int $quantity, array $option_ids): void
    {
        $user_id = Auth::id();

        $cartItem = CartItems::where('user_id', $user_id)->where('product_id', $product_id)->where('variation_type_option_ids', json_encode($option_ids))->first();

        if($cartItem) {
          $cartItem->update([
            'quantity' => $quantity
          ]);
        }

    }

    protected function updateItemQuantityInCookies(int $product_id, int $quantity, array $option_ids): void
    {
        $cartItems = $this->getCartItemsFromCookies();

        ksort($option_ids);

        $item_key = $product_id . '_' . json_encode($option_ids);

        if(isset($cartItems[$item_key])) {
          $cartItems[$item_key]['quantity'] = $quantity;
        }

        Cookie::queue(self::COOKIE_NAME, json_encode($cartItems), self::COOKIE_LIFETIME);

    }

    protected function saveItemToDatabase(int $product_id, int $quantity, $price, array $option_ids):void
    {
        $user_id = Auth::id();
        ksort($option_ids);

        $cartItems = CartItems::query()->where('user_id', $user_id)->where('product_id', $product_id)->where('variation_type_option_ids', json_encode($option_ids))->first();

        if($cartItems) {
          $cartItems->update([
            'quantity' => DB::raw('quantity + ' . $quantity)
          ]);
        } else{
          CartItems::create([
            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price,
            'variation_type_option_ids' => json_encode($option_ids)

          ]);
        }

    }
    protected function saveItemToCookies(int $product_id, int $quantity, $price, array $option_ids):void
    {
        $cartItems = $this->getCartItemsFromCookies();
//        dd($cartItems, $quantity, $price, $option_ids);
        ksort($option_ids);
        $item_key = $product_id . '_' . json_encode($option_ids);
        if(isset($cartItems[$item_key])) {
          $cartItems[$item_key]['quantity'] += $quantity;
          $cartItems[$item_key]['price'] = $price;
        } else{
          $cartItems[$item_key] = [
            'id'=>\Str::uuid(),
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price,
            'option_ids' => $option_ids
          ];
        }

        Cookie::queue(self::COOKIE_NAME, json_encode($cartItems), self::COOKIE_LIFETIME);

    }
    protected function removeItemFromDatabase(int $product_id, array $option_ids): void
    {
      $user_id = Auth::id();

      ksort($option_ids);

      CartItems::where('user_id', $user_id)->where('product_id', $product_id)->where('variation_type_option_ids', json_encode($option_ids))->delete();

    }
    protected function removeItemFromCookies(int $product_id, array $option_ids): void
    {
      $cartItems = $this->getCartItemsFromCookies();
      ksort($option_ids);

      $item_key = $product_id . '_' . json_encode($option_ids);

      unset($cartItems[$item_key]);

      Cookie::queue(self::COOKIE_NAME, json_encode($cartItems), self::COOKIE_LIFETIME);

    }
    protected function getCartItemsFromDatabase()
    {
        $user_id = Auth::id();
        $cartItems = CartItems::where('user_id', $user_id)->get()->map(function ($cartItem) {
          return [
            'id' => $cartItem->id,
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->price,
            'option_ids' => json_decode($cartItem->variation_type_option_ids, true),
          ];
        })->toArray();

//        dd($cartItems);
        return $cartItems;


    }
    protected function getCartItemsFromCookies()
    {
        $cartItems = json_decode(Cookie::get(self::COOKIE_NAME, '[]'), true);

        return $cartItems;
    }

  public function getCartItemGrouped():array
  {
    $cartItems = $this->getCartItems();

    return collect($cartItems)
      ->groupBy(fn($item)=> $item['user']['id'])
      ->map(fn($item, $userId)=>[
        'user'=>$item->first()['user'],
        'items'=>$item->toArray(),
        'totalQuantity'=>$item->sum('quantity'),
        'totalPrice'=>$item->map(fn($item)=>$item['price'] * $item['quantity'])
      ])->toArray();
  }

  public function  movingCartItemsToDatabase($userId):void
  {
    $cartItems = $this->getCartItemsFromCookies();

    foreach ($cartItems as $cartItem) {
      $exstingItems = CartItems::where('user_id', $userId)
        ->where('product_id', $cartItem['product_id'])
        ->where('variation_type_option_ids', json_encode($cartItem['option_ids']))
        ->first();

    if($exstingItems) {
      CartItems::updated([
        'quantity' => $exstingItems->quantity + $cartItem['quantity'],
        'price' => $cartItem['price']
      ]);
    } else{
      CartItems::create([
        'user_id' => $userId,
        'product_id' => $cartItem['product_id'],
        'quantity' => $cartItem['quantity'],
        'price' => $cartItem['price'],
        'variation_type_option_ids' => json_encode($cartItem['option_ids'])
      ]);
    }
    }


    Cookie::queue(self::COOKIE_NAME, '', -1);

  }
}
