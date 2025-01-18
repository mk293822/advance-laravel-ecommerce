<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array(
          'id'=> $this->id,
          'title' => $this->title,
          'slug' => $this->slug,
          'price' => $this->price,
          'quantity'=> $this->quantity,
          'description' => $this->description,
          'image'=> $this->getFirstMediaUrl('images', 'small'),
          'images' => $this->getMedia('images')->map(function ($image) {
            return array(
              'id' => $image->id,
              'thumb' => $image->getUrl('thumb'),
              'large' => $image->getUrl('large'),
              'small' => $image->getUrl('small'),
            );
          }),
          'user' => array(
            'name'=>$this->user->name,
            'id'=>$this->user->id,
          ),
          'department'=> array(
            'id'=>$this->department->id,
            'name'=>$this->department->name,
          ),
          'variationTypes' => $this->variationTypes->map(function ($variationType) {
            return array(
              'id' => $variationType->id,
              'name' => $variationType->name,
              'type' => $variationType->type,
              'options' => $variationType->options->map(function ($option) {
                return array(
                  'id' => $option->id,
                  'name' => $option->name,
                  'images' => $option->getMedia('images')->map(function ($image) {
                    return array(
                      'id' => $image->id,
                      'thumb' => $image->getUrl('thumb'),
                      'large' => $image->getUrl('large'),
                      'small' => $image->getUrl('small'),
                    );
                  })
                );
              }),
            );
          }),
          'variations' => $this->variations->map(function ($variation) {
            return array(
              'id' => $variation->id,
              'variation_type_option_ids' => $variation->variation_type_option_ids,
              'quantity' => $variation->quantity,
              'price' => $variation->price,
            );
          })
        );
    }
}
