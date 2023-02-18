<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EditSettingPolicy
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

    public function viewAny(User $user)
    {
        return $user->can('edit_settings');
    }

    public function create(User $user)
    {
        return $user->can('edit_settings');
    }

    public function view(User $user)
    {
        return $user->can('edit_settings');
    }

    public function delete(User $user)
    {
        return $user->can('edit_settings');
    }

    public function update(User $user)
    {
        return $user->can('edit_settings');
    }
}
