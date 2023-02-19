<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }

    public function destroy(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }
    public function delete(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }
    public function create(){}

    public function view(){}

    public function viewAny(User $user)
    {
        return $user->can('edit_settings');
    }
}
