<?php

declare(strict_types=1);

namespace App\Modules\CarRental\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Modules\CarRental\Models\Customer;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Manager, UserRole::Agent], true);
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Customer $customer): bool
    {
        return true;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasRole(UserRole::Admin);
    }
}
