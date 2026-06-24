<?php

declare(strict_types=1);

namespace App\Modules\Vehicles\Types;

enum VehicleStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Rented = 'rented';
    case Maintenance = 'maintenance';
    case Unavailable = 'unavailable';
}
