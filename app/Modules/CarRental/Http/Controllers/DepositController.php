<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Actions\UpdateDepositAction;
use App\Modules\CarRental\Http\Requests\DepositRequest;
use App\Modules\CarRental\Models\Deposit;
use Illuminate\Http\RedirectResponse;

class DepositController extends Controller
{
    public function __construct(private readonly UpdateDepositAction $updateDepositAction) {}

    public function update(DepositRequest $request, Deposit $deposit): RedirectResponse
    {
        $this->authorize('update', $deposit->rental);
        $this->updateDepositAction->execute($deposit, $request->validated());

        return back()->with('status', 'Deposit updated successfully.');
    }
}
