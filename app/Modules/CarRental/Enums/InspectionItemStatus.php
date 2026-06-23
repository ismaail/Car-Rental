<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Enums;

enum InspectionItemStatus: string
{
    case Ok = 'ok';
    case Noted = 'noted';
    case Damaged = 'damaged';
}
