<?php

namespace App\Models;

use App\Enums\VendorEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
  use HasFactory;
 protected  $primaryKey = 'user_id';

  public function scopeEligibleForPayout(Builder $query): Builder
  {
    return $query
      ->where('status', VendorEnum::Approved)
      ->join('users', 'users.id', '=', 'vendors.user_id')
      ->where('users.stripe_account_active', true);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

}
