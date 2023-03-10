<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 生成数据集合
        User::factory()->count(10)->create();

        // 单独处理第一个用户的数据
        $user1 = User::find(1);
        //将一号用户分配站长角色
        
        $user1->name = 'liu';
        $user1->email = 'founder@lf.com';
        $user1->avatar = 'avatars/default/founder.png';
        $user1->assignRole('founder');

        //将二号用户分配管理员角色
        $user2 = User::find(2);
        $user2->email = 'manager@lf.com';
        $user2->assignRole('Maintainer');

        $user1->save();
        $user2->save();
    }
}