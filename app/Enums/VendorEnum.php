<?php

namespace App\Enums;

enum VendorEnum: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'Rejected';
}
