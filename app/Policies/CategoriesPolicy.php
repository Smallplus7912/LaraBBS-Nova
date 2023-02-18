<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriesPolicy extends Policy
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

    public function create() {}

    public function delete() {}

    public function update() {}

    public function view() {}
}
