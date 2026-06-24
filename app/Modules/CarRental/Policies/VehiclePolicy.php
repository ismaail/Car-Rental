<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\Vehicle\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager, UserRole::Agent], true);
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->canManageCarRentalOperations();
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->canManageCarRentalOperations();
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole(UserRole::Admin);
    }
}
