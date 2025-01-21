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

      $options = $request->input('options') ?: [];

      if($options){
        $images = $this->getImagesForOptions($options);
      }else {
        $images = $this->getImages();
      }



        return array(
          'id'=> $this->id,
          'title' => $this->title,
          'slug' => $this->slug,
          'price' => $this->price,
          'quantity'=> $this->quantity,
          'description' => $this->description,
          'meta_title'=> $this->meta_title,
          'meta_description'=> $this->meta_description,
          'image'=> $this->getFirstMediaUrl('images', 'small'),
          'images' => $images->map(function ($image) {
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
            'store_name'=>$this->user->vendor->store_name,
          ),
          'department'=> array(
            'id'=>$this->department->id,
            'name'=>$this->department->name,
            'slug'=>$this->department->slug,
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
