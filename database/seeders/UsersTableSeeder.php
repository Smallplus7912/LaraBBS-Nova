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
        $user = User::find(1);
        //将一号用户分配站长角色
        $user->assignRole('Founder');
        $user->name = 'liu';
        $user->email = 'summer@example.com';
        $user->avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';

        //将二号用户分配管理员角色
        $user = User::find(2);
        $user->assignRole('Maintainer');

        $user->save();
    }
}