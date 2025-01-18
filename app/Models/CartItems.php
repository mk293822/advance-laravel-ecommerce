<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    protected $casts = [
      'variation_type_option_ids' => 'array'
    ];
}
