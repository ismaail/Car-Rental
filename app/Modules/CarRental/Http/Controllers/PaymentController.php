<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Http\Requests\PaymentRequest;
use App\Modules\CarRental\Services\PaymentService;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function store(PaymentRequest $request): RedirectResponse
    {
        $this->paymentService->create($request->validated());

        return back()->with('status', 'Payment recorded successfully.');
    }
}
