<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Http\Requests\ReservationRequest;
use App\Modules\CarRental\Models\Customer;
use App\Modules\CarRental\Services\RentalService;
use App\Modules\CarRental\Services\ReservationService;
use App\Modules\Reservation\Actions\CreateReservationAction;
use App\Modules\Reservation\DataObjects\ReservationData;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Vehicle\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationService $reservationService,
        private readonly RentalService $rentalService,
    ) {
        $this->authorizeResource(Reservation::class, 'reservation');
    }

    public function index(): View
    {
        return view('car-rental.reservations.index', [
            'reservations' => Reservation::query()->with(['vehicle', 'customer'])->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('car-rental.reservations.form', [
            'reservation' => new Reservation(),
            'vehicles' => Vehicle::query()->orderBy('brand')->orderBy('model')->get(),
            'customers' => Customer::query()->orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function store(ReservationRequest $request): RedirectResponse
    {
        $reservation = CreateReservationAction::run(
            ReservationData::fromRequest($request),
        );

        return redirect()->route('car-rental.reservations.show', $reservation)
            ->with('status', 'Reservation created successfully.')
        ;
    }

    public function show(Reservation $reservation): View
    {
        return view('car-rental.reservations.show', [
            'reservation' => $reservation->load(['vehicle', 'customer']),
        ]);
    }

    public function edit(Reservation $reservation): View
    {
        return view('car-rental.reservations.form', [
            'reservation' => $reservation,
            'vehicles' => Vehicle::query()->orderBy('brand')->orderBy('model')->get(),
            'customers' => Customer::query()->orderBy('first_name')->orderBy('last_name')->get(),
        ]);
    }

    public function update(ReservationRequest $request, Reservation $reservation): RedirectResponse
    {
        $this->reservationService->update($reservation, $request->validated());

        return redirect()->route('car-rental.reservations.show', $reservation)
            ->with('status', 'Reservation updated successfully.')
        ;
    }

    public function confirm(Reservation $reservation): RedirectResponse
    {
        $this->authorize('confirm', $reservation);
        $this->reservationService->confirm($reservation);

        return back()->with('status', 'Reservation confirmed successfully.');
    }

    public function convert(Reservation $reservation): RedirectResponse
    {
        $this->authorize('convert', $reservation);

        $rental = $this->rentalService->activateFromReservation($reservation);

        return redirect()->route('car-rental.rentals.show', $rental)
            ->with('status', 'Reservation converted to rental successfully.')
        ;
    }
}
