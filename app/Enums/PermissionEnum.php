<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case ApproveVendors = "ApproveVenders";
    case SellProducts = "SellProducts";
    case BuyProducts = "BuyProducts";
}