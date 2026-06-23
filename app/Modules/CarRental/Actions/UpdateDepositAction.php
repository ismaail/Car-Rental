<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Actions;

use App\Modules\CarRental\Enums\DepositStatus;
use App\Modules\CarRental\Models\Deposit;
use Illuminate\Support\Facades\DB;

class UpdateDepositAction
{
    /**
     * @param array<string, mixed> $data
     */
    public function execute(Deposit $deposit, array $data): Deposit
    {
        return DB::transaction(function () use ($deposit, $data): Deposit {
            $deposit->update([
                'status' => $data['status'],
                'collected_at' => DepositStatus::Collected === $data['status'] ? now() : $deposit->collected_at,
                'released_at' => in_array($data['status'], [DepositStatus::Released, DepositStatus::PartiallyWithheld], true) ? now() : null,
                'withheld_amount' => $data['withheld_amount'] ?? 0,
                'notes' => $data['notes'] ?? $deposit->notes,
            ]);

            return $deposit->refresh();
        });
    }
}
