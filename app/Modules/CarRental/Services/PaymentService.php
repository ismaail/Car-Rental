<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Services;

use App\Modules\CarRental\Models\Payment;

class PaymentService
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Payment
    {
        return Payment::query()->create($data);
    }
}
