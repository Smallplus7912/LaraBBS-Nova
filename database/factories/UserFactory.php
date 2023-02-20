<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $avatars = [
            'avatars/default/male.png',
            'avatars/default/male2.png',
            'avatars/default/famle.png',
            'avatars/default/famle2.png',
        ];
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'introduction' => $this->faker->sentence(),
            'avatar' => $this->faker->randomElement($avatars),
            'created_at' => $this->faker->dateTimeBetween('2023-01-01', '2023-02-14'),
            'updated_at' => $this->faker->dateTimeBetween('2023-02-15', 'now')
        ];
    }
}