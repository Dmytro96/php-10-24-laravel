<?php

namespace App\Enums;

enum TransactionStatusesEnum: string
{
    case Success = 'success';
    case Cancelled = 'cancelled';
    case Pending = 'pending';
}
