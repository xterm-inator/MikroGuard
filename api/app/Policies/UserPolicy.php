<?php

namespace App\Policies;

use App\Models\User;
use App\Support\Enums\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $loggedInUser): bool
    {
        return $loggedInUser->role == Role::Admin;
    }

    public function store(User $loggedInUser): bool
    {
        return $loggedInUser->role == Role::Admin;
    }

    public function show(User $loggedInUser, User $user): bool
    {
        return $loggedInUser->role == Role::Admin || $loggedInUser->id == $user->id;
    }

    public function delete(User $loggedInUser): bool
    {
        return $loggedInUser->role == Role::Admin;
    }

    public function config(User $loggedInUser, User $user): bool
    {
        return $loggedInUser->role == Role::Admin || $loggedInUser->id == $user->id;
    }
}
