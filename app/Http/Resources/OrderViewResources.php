<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPSTORM_META\map;

class OrderViewResources extends JsonResource
{
  public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id'=> $this->id,
          'total_price'=>$this->total_price,
          'status'=>$this->status,
          'created'=>$this->created_at->format('Y-m-d H:i:s'),
          'vendorUser'=>new VendorUserResource($this->vendorUser),
          'orderItems'=> $this->orderItems->map(fn ($item) => [
              'id'=>$item->product->id,
              'title'=>$item->product->title,
              'slug'=>$item->product->slug,
              'description'=>$item->product->description,
              'image'=>$item->product->getImageForOptions($item->variation_typ_option_ids?: []),
            ]),
        ];
    }
}
