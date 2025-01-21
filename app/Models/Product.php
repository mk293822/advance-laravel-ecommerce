<?php

namespace App\Models;

use App\Enums\Enums\ProductStatusEnum;
use App\Enums\VendorEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

//     protected $casts = [
//         "variations" => "array",
//     ];

    public function scopeForVendor(Builder $query): Builder
    {
      return $query->where('created_by', auth()->user()->id);
    }

    public function scopePublished(Builder $query): Builder
    {
      return $query->where('products.status', ProductStatusEnum::Published);
    }

    public function scopeForWebsite(Builder $query): Builder
    {
      return $query->published()->vendorApproved();
    }

  public function scopeVendorApproved(Builder $query): Builder
  {
    return $query->join('vendors', 'vendors.user_id', '=', 'products.created_by')
      ->where('vendors.status', VendorEnum::Approved->value);
  }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100);

        $this->addMediaConversion('small')
            ->width(480);

        $this->addMediaConversion('large')
            ->width(1200);
    }

  public function options(): HasManyThrough
  {
    return $this->hasManyThrough(
      VariationTypeOption::class,
      VariationType::class,
      'product_id',
      'variation_type_id',
      'id',
      'id',
    )  ;
  }


    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class, 'created_by');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }
    public function variationTypes(): HasMany
    {
        return $this->hasMany(VariationType::class);
    }

  public function getPriceForOption($option_ids = [])
  {
    $option_ids = array_values($option_ids);
    sort($option_ids);

    foreach ($this->variations as $variation) {
      $a = $variation->variation_type_option_ids;
      sort($a);
      if($option_ids == $a) {
        return $variation->price !== null > $variation->price ? $variation->price : $this->price;
      }
    }
    return $this->price;
  }

  public function getImageForOptions(array $option_ids = null)
  {
    if($option_ids) {
      $optionIds = array_values($option_ids);
      sort($optionIds);
      $options = VariationTypeOption::whereIn('id', $optionIds)->get();

      foreach ($options as $option) {
        $image = $option->getFirstMediaUrl('images', 'small');
        if($image) {
          return $image;
        }
      }
    }
    return $this->getFirstMediaUrl('images', 'small');

  }

  public function getImagesForOptions(array $option_ids = null)
  {
    if($option_ids){
      $option_ids = array_values($option_ids);
      $options = VariationTypeOption::whereIn('id', $option_ids)->get();

      foreach ($options as $option) {
        $images = $option->getMedia('images');
        if($images) {
          return $images;
        }
      }
    }
    return $this->getMedia('images');
  }

  public function getFirstImageUrl($collectionName = 'images', $conversion = 'small'):string
  {
//    dd($this->options()->count());
    if ($this->options->count() > 1) {
//      dd($this->options);
      foreach ($this->options as $option) {
//        dd($option);
        $image_url = $option->getFirstMediaUrl($collectionName, $conversion);
        if($image_url) {
//          dd($image_url);
          return $image_url;
        }
      }
    }
//    dd($this->getFirstMediaUrl($collectionName, $conversion));
    return $this->getFirstMediaUrl($collectionName, $conversion);
  }

  public function getImages():MediaCollection
  {
    if ($this->options->count() > 1) {
      foreach ($this->options as $option) {
        $images = $option->getMedia('images');
        if($images) {
          return $images;
        }
      }
    }
    return $this->getMedia('images');
  }


  public function getFirstPrice():float
  {
    $firstOption = $this->getFirstOptionsMap();

    if($firstOption) {
      return $this->getPriceForOption();
    }
    return $this->price;
  }

  public function getFirstOptionsMap()
  {
    return $this->variationTypes->mapWithKeys(fn($type)=> [$type->id => $type->options[0]?->id])->toArray();
  }

}
