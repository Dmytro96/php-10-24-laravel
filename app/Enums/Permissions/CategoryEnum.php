<?php

namespace App\Enums\Permissions;

use App\Traits\EnumValues;

enum CategoryEnum: string
{
    use EnumValues;
    
    case PUBLISH = 'publish category';
    case EDIT = 'edit category';
    case DELETE = 'delete category';
}
