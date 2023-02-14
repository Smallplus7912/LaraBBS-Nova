<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition()
    {
        return [
            //回复内容：faker类，sentence方法
            'content' => $this->faker->sentence(),
            //回复的id：随机数1-62
            'topic_id' => rand(1,50),
            //用户id：1-10随机
            'user_id' => rand(1,10),
        ];
    }
}