<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Teacher;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['PRESENT', 'ABSENT', 'LATE']);
        $isPresent = $status === 'PRESENT' || $status === 'LATE';

        return [
            'student_id' => Student::inRandomOrder()->first()?->id ?? Student::factory(),
            'class_id' => Classroom::inRandomOrder()->first()?->id ?? Classroom::factory(),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'status' => $status,
            'check_in_time' => $isPresent ? $this->faker->time('H:i:s') : null,
            'absence_reason' => $status === 'ABSENT' ? $this->faker->sentence : null,
            'remarks' => $this->faker->optional()->paragraph,
            'recorded_by' => Teacher::inRandomOrder()->first()?->id ?? Teacher::factory(),
        ];
    }
}
