<?php

declare(strict_types=1);

namespace App\Modules\Reservations\Types;

enum ReservationStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Converted = 'converted';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
}
