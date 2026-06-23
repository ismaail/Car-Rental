<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\CarRental\Models\Rental;

class RentalPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager, UserRole::Agent], true);
    }

    public function view(User $user, Rental $rental): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Rental $rental): bool
    {
        return true;
    }

    public function delete(User $user, Rental $rental): bool
    {
        return $user->hasRole(UserRole::Admin);
    }

    public function return(User $user, Rental $rental): bool
    {
        return true;
    }
}
