<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\CarRental\Enums\VehicleStatus;
use App\Modules\CarRental\Models\Customer;
use App\Modules\CarRental\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Agency Admin',
            'email' => 'admin@esoukari.test',
        ]);

        User::factory()->manager()->create([
            'name' => 'Operations Manager',
            'email' => 'manager@esoukari.test',
        ]);

        User::factory()->create([
            'name' => 'Rental Agent',
            'email' => 'agent@esoukari.test',
            'role' => UserRole::Agent,
        ]);

        Vehicle::factory()->count(8)->create([
            'status' => VehicleStatus::Available,
        ]);

        Customer::factory()->count(12)->create();
    }
}
