<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition()
    {
        //我在这里增加一行，测试一下git pull
        return [
            //回复内容：faker类，sentence方法
            'content' => $this->faker->sentence(),
            //回复的id：随机数1-62
            'topic_id' => rand(1,20),
            //用户id：1-10随机
            'user_id' => rand(1,10),
            'created_at' => $this->faker->dateTimeThisMonth(),
            'updated_at' => $this->faker->dateTimeThisMonth()            
        ];
    }
}
