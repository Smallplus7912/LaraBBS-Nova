<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->isHasPermission('manage_users');
    }

    public function update(User $currentUser, User $user)
    {
        return $user->isHasPermission('manage_users') || $currentUser->id === $user->id;
    }

    public function delete(User $currentUser, User $user)
    {
        return $user->isHasPermission('manage_users') && !$user->hasRole('founder');
    }

    public function view(User $user)
    {
        return $user->isHasPermission('manage_users');
    }

    public function viewAny(User $user)
    {
        return $user->can('manage_users');
    }
}
