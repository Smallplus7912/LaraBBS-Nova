<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Log;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public function saving(User $user)
    {
        if(empty($user->avatar)){
            $user->avatar = 'https://cdn.learnku.com/uploads/images/201710/30/1/TrJS40Ey5k.png';
        }
    }

    public function updating(User $user)
    {
        //
    }
    //删除用户的级联删除
    public function deleted(User $user)
    {
        $topic = Topic::query()->where('user_id', $user->id)->pluck('id');
        Topic::query()->whereIn('id', $topic)->delete();
        Reply::query()->whereIn('topic_id', $topic)->delete();
    }
}