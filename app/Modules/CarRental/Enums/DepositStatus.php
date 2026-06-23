<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Enums;

enum DepositStatus: string
{
    case Pending = 'pending';
    case Collected = 'collected';
    case PartiallyWithheld = 'partially_withheld';
    case Released = 'released';
}
