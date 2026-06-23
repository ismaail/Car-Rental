<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Card = 'card';
    case BankTransfer = 'bank_transfer';
    case Check = 'check';
}
