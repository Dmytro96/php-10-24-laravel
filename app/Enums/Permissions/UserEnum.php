<?php

namespace App\Enums\Permissions;

use App\Traits\EnumValues;

enum UserEnum: string
{
    use EnumValues;
    
    case PUBLISH = 'publish user';
    case EDIT = 'edit user';
    case DELETE = 'delete user';
}
