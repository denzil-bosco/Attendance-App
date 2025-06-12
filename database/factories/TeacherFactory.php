<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('secret123'),
            'phone_number' => $this->faker->unique()->phoneNumber,
            'subject' => $this->faker->sentence(3),
            'status' => 'ACTIVE',
        ];
    }
}
