<?php

namespace App\Enums;


use App\Traits\EnumValues;

enum OrderStatusEnum: string
{
    use EnumValues;

    case InProcess = 'in_process';
    case Paid = 'paid';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
