<?php

namespace App\Enums\Permissions;

use App\Traits\EnumValues;

enum ProductEnum: string
{
    use EnumValues;
    
    case PUBLISH = 'publish product';
    case EDIT = 'edit product';
    case DELETE = 'delete product';
}
