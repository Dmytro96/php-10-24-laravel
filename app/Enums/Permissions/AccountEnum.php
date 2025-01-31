<?php

namespace App\Enums\Permissions;

use App\Traits\EnumValues;

enum AccountEnum: string
{
    use EnumValues;
    
    case EDIT = 'edit account';
    case DELETE = 'delete account';
}
