<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\Reservation\Models\Reservation;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager, UserRole::Agent], true);
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return true;
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return UserRole::Agent !== $user->role;
    }

    public function confirm(User $user, Reservation $reservation): bool
    {
        return true;
    }

    public function convert(User $user, Reservation $reservation): bool
    {
        return true;
    }
}
