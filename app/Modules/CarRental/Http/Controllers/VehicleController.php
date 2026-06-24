<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Http\Requests\VehicleRequest;
use App\Modules\CarRental\Services\VehicleService;
use App\Modules\Vehicles\Models\Vehicle;
use App\Modules\Vehicles\Types\VehicleStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class VehicleController extends Controller
{
    public function __construct(private readonly VehicleService $vehicleService)
    {
        $this->authorizeResource(Vehicle::class, 'vehicle');
    }

    public function index(): View
    {
        return view('car-rental.vehicles.index', [
            'vehicles' => Vehicle::query()->latest()->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('car-rental.vehicles.form', [
            'vehicle' => new Vehicle(),
            'statuses' => VehicleStatus::cases(),
        ]);
    }

    public function store(VehicleRequest $request): RedirectResponse
    {
        $vehicle = $this->vehicleService->create($request->validated());

        return redirect()->route('car-rental.vehicles.show', $vehicle)
            ->with('status', 'Vehicle created successfully.')
        ;
    }

    public function show(Vehicle $vehicle): View
    {
        return view('car-rental.vehicles.show', [
            'vehicle' => $vehicle->load(['images', 'reservations.customer', 'rentals.customer']),
        ]);
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('car-rental.vehicles.form', [
            'vehicle' => $vehicle,
            'statuses' => VehicleStatus::cases(),
        ]);
    }

    public function update(VehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $this->vehicleService->update($vehicle, $request->validated());

        return redirect()->route('car-rental.vehicles.show', $vehicle)
            ->with('status', 'Vehicle updated successfully.')
        ;
    }
}
