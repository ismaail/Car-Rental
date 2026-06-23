<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Models\Rental;
use App\Modules\CarRental\Services\ContractPdfService;
use App\Modules\CarRental\Services\RentalService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class RentalController extends Controller
{
    public function __construct(
        private readonly RentalService $rentalService,
        private readonly ContractPdfService $contractPdfService,
    ) {
        $this->authorizeResource(Rental::class, 'rental');
    }

    public function index(): View
    {
        $this->rentalService->markOverdueRentals();

        return view('car-rental.rentals.index', [
            'rentals' => Rental::query()->with(['vehicle', 'customer', 'deposit'])->latest()->paginate(12),
        ]);
    }

    public function show(Rental $rental): View
    {
        return view('car-rental.rentals.show', [
            'rental' => $rental->load([
                'vehicle',
                'customer',
                'reservation',
                'inspections.items',
                'deposit',
                'payments',
            ]),
        ]);
    }

    public function processReturn(Rental $rental): RedirectResponse
    {
        $this->authorize('return', $rental);
        $this->rentalService->processReturn($rental);

        return back()->with('status', 'Rental returned successfully.');
    }

    public function contract(Rental $rental): Response
    {
        $pdf = $this->contractPdfService->output($rental);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $rental->rental_number . '.pdf"',
        ]);
    }
}
