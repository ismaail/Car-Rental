<?php

declare(strict_types=1);

use App\Modules\CarRental\Http\Controllers\CustomerController;
use App\Modules\CarRental\Http\Controllers\DashboardController;
use App\Modules\CarRental\Http\Controllers\DepositController;
use App\Modules\CarRental\Http\Controllers\InspectionController;
use App\Modules\CarRental\Http\Controllers\PaymentController;
use App\Modules\CarRental\Http\Controllers\RentalController;
use App\Modules\CarRental\Http\Controllers\ReservationController;
use App\Modules\CarRental\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('car-rental')
    ->name('car-rental.')
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('vehicles', VehicleController::class)->except(['destroy']);
        Route::resource('customers', CustomerController::class)->except(['destroy']);
        Route::resource('reservations', ReservationController::class)->except(['destroy']);
        Route::post('reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm');
        Route::post('reservations/{reservation}/convert', [ReservationController::class, 'convert'])->name('reservations.convert');

        Route::resource('rentals', RentalController::class)->only(['index', 'show']);
        Route::post('rentals/{rental}/return', [RentalController::class, 'processReturn'])->name('rentals.return');

        Route::post('rentals/{rental}/inspections', [InspectionController::class, 'store'])->name('inspections.store');
        Route::patch('deposits/{deposit}', [DepositController::class, 'update'])->name('deposits.update');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('rentals/{rental}/contract', [RentalController::class, 'contract'])->name('rentals.contract');
    })
;
