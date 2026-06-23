<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Enums;

enum InspectionType: string
{
    case Pickup = 'pickup';
    case Return = 'return';
}
