<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //填充用户表
        $this->call(UsersTableSeeder::class);
		//填充帖子
        $this->call(TopicsTableSeeder::class);
        //填充回复
        $this->call(RepliesTableSeeder::class);
    }
}
