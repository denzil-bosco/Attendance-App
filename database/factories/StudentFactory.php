<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use \App\Models\Classroom;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date('Y-m-d', '-5 years'),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE', 'OTHER']),
            'contact_person_name' => $this->faker->name,
            'contact_person_phone' => $this->faker->phoneNumber,
            'enrollment_date' => $this->faker->date('Y-m-d'),
            'student_id_number' => $this->faker->unique()->numerify('STD-#####'),
            'class_id' => Classroom::factory(),
        ];
    }
}
