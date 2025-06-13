<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

class ClassroomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'grade' => $this->faker->randomElement(['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4']),
            'room_number' => $this->faker->bothify('Room ###'),
            'total_students' => $this->faker->numberBetween(10, 50),
            'teacher_id' => Teacher::factory(),
        ];
    }
}
