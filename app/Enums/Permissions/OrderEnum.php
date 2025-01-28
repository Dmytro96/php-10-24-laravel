<?php

namespace App\Enums\Permissions;

use App\Traits\EnumValues;

enum OrderEnum: string
{
    use EnumValues;
    
    case EDIT = 'edit order';
    case DELETE = 'delete order';
}
