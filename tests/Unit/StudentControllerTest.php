<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_students()
    {
        $classroom = Classroom::factory()->create();
        $students = Student::factory()->count(3)->create(['class_id' => $classroom->id]);

        $response = $this->getJson('/api/student');
        $response->assertStatus(200);

        foreach ($students as $student) {
            $response->assertJsonFragment([
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'class_id' => $student->class_id,
            ]);
        }
    }

    public function test_store_creates_new_student()
    {
        $classroom = Classroom::factory()->create();

        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '2010-01-01',
            'gender' => 'MALE',
            'contact_person_name' => 'Jane Doe',
            'contact_person_phone' => '1234567890',
            'enrollment_date' => '2023-06-01',
            'student_id_number' => 'STU12345',
            'class_id' => $classroom->id,
        ];

        $response = $this->postJson('/api/student', $payload);

        $response->assertStatus(201)
        ->assertJsonFragment([
         'first_name' => 'John',
         'last_name' => 'Doe',
         'student_id_number' => 'STU12345',
         'class_id' => $classroom->id,
     ]);

        $this->assertDatabaseHas('students', $payload);
    }

    public function test_show_returns_student_data()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['class_id' => $classroom->id]);

        $this->getJson("/api/student/{$student->id}")
        ->assertStatus(200)
        ->assertJsonFragment([
         'id' => $student->id,
         'first_name' => $student->first_name,
         'last_name' => $student->last_name,
         'student_id_number' => $student->student_id_number,
         'class_id' => $student->class_id,
     ]);

        $this->getJson("/api/student/999")
        ->assertStatus(404)
        ->assertJsonFragment([
         'error' => "Student with ID(s) 999 not found.",
     ]);
    }

    public function test_update_modifies_student_data()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['class_id' => $classroom->id]);

        $updateData = [
            'first_name' => 'UpdatedFirst',
            'last_name' => 'UpdatedLast',
            'class_id' => $classroom->id,
        ];

        $this->putJson("/api/student/{$student->id}", $updateData)
        ->assertStatus(200)
        ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('students', $updateData);

        $this->putJson("/api/student/999", $updateData)
        ->assertStatus(404)
        ->assertJsonFragment([
         'error' => "Student with ID(s) 999 not found.",
     ]);
    }

    public function test_destroy_deletes_student()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['class_id' => $classroom->id]);

        $this->deleteJson("/api/student/{$student->id}")
        ->assertStatus(204);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);

        $this->getJson("/api/student/{$student->id}")
        ->assertStatus(404)
        ->assertJsonFragment([
         'error' => "Student with ID(s) {$student->id} not found.",
     ]);
    }

    public function test_student_store_validation_errors()
    {
        $response = $this->postJson('/api/student', []);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'first_name' => ['The first name field is required.'],
            'last_name' => ['The last name field is required.'],
            'class_id' => ['The class id field is required.'],
        ]);
    }

    public function test_student_store_invalid_values()
    {
        $classroom = Classroom::factory()->create();

        $payload = [
            'first_name' => 1,
            'last_name' => 2,
            'date_of_birth' => '2050-01-01',
            'gender' => 'INVALID',
            'contact_person_name' => 3,
            'contact_person_phone' => 1234567890,
            'enrollment_date' => '2050-01-01',
            'student_id_number' => 'STU0001',
            'class_id' => $classroom->id,
        ];

        $response = $this->postJson('/api/student', $payload);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'first_name' => ['The first name field must be a string.'],
            'last_name' => ['The last name field must be a string.'],
            'date_of_birth' => ['The date of birth field must be a date before or equal to today.'],
            'gender' => ['The selected gender is invalid.'],
            'contact_person_name' => ['The contact person name field must be a string.'],
            'contact_person_phone' => ['The contact person phone field must be a string.'],
            'enrollment_date' => ['The enrollment date field must be a date before or equal to today.'],
        ]);
    }
}
