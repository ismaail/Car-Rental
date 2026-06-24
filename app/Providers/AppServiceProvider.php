<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modules\CarRental\Models\Rental;
use App\Modules\CarRental\Policies\RentalPolicy;
use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Policies\CustomerPolicy;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Reservation\Policies\ReservationPolicy;
use App\Modules\Vehicle\Models\Vehicle;
use App\Modules\Vehicle\Policies\VehiclePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! app()->isProduction());

        Gate::policy(Vehicle::class, VehiclePolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Reservation::class, ReservationPolicy::class);
        Gate::policy(Rental::class, RentalPolicy::class);
    }
}
