<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        Classroom::factory()->count(5)->create();
    }
}
