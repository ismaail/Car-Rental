<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CarRental\Enums\RentalStatus;
use App\Modules\CarRental\Models\Customer;
use App\Modules\CarRental\Models\Rental;
use App\Modules\CarRental\Models\Reservation;
use App\Modules\CarRental\Models\Vehicle;
use App\Modules\Vehicles\Types\VehicleStatus;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('car-rental.dashboard', [
            'stats' => [
                'vehicles' => Vehicle::query()->count(),
                'availableVehicles' => Vehicle::query()->where('status', VehicleStatus::Available)->count(),
                'activeReservations' => Reservation::query()->whereIn('status', ['pending', 'confirmed'])->count(),
                'activeRentals' => Rental::query()->whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])->count(),
                'customers' => Customer::query()->count(),
            ],
            'upcomingReservations' => Reservation::query()
                ->with(['vehicle', 'customer'])
                ->orderBy('pickup_at')
                ->limit(5)
                ->get(),
            'overdueRentals' => Rental::query()
                ->with(['vehicle', 'customer'])
                ->whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])
                ->where('ends_at', '<', now())
                ->orderBy('ends_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
