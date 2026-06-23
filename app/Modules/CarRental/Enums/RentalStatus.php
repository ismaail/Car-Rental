<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Enums;

enum RentalStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Overdue = 'overdue';
}
