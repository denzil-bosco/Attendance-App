<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassroomControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_classroom_list()
    {
        $classrooms = Classroom::factory()->count(3)->create();

        $response = $this->getJson('/api/classroom');

        $response->assertStatus(200);

        foreach ($classrooms as $classroom) {
            $response->assertJsonFragment([
                'id' => $classroom->id,
                'grade' => $classroom->grade,
                'room_number' => $classroom->room_number,
                'teacher_id' => $classroom->teacher_id,
                'total_students' => $classroom->total_students,
            ]);
        }
    }

    public function test_store_creates_classroom()
    {
        $teacher = Teacher::factory()->create();

        $payload = [
            'grade' => 'Grade 1',
            'room_number' => 'A101',
            'total_students' => '30',
            'teacher_id' => $teacher->id,
        ];

        $response = $this->postJson('/api/classroom', $payload);

        $response->assertStatus(201)
        ->assertJsonFragment($payload);

        $this->assertDatabaseHas('classrooms', $payload);
    }

    public function test_show_returns_single_classroom()
    {
        $classroom = Classroom::factory()->create();

        $this->getJson("/api/classroom/{$classroom->id}")
        ->assertStatus(200)
        ->assertJsonFragment([
            'grade' => $classroom->grade,
            'room_number' => $classroom->room_number,
            'total_students' => $classroom->total_students,
            'teacher_id' => $classroom->teacher_id,
        ]);

        $this->getJson("/api/classroom/999")
        ->assertStatus(404)
        ->assertJsonFragment([
            'error' => "Classroom with ID(s) 999 not found.",
        ]);
    }

    public function test_update_updates_classroom()
    {
        $classroom = Classroom::factory()->create();

        $updateData = [
            'grade' => 'Grade 2 Updated',
            'room_number' => 'B202',
        ];

        $this->putJson("/api/classroom/{$classroom->id}", $updateData)
        ->assertStatus(200)
        ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('classrooms', $updateData);

        $this->putJson("/api/classroom/999", $updateData)
        ->assertStatus(404)
        ->assertJsonFragment([
            'error' => "Classroom with ID(s) 999 not found.",
        ]);
    }

    public function test_destroy_deletes_classroom()
    {
        $classroom = Classroom::factory()->create();

        $this->deleteJson("/api/classroom/{$classroom->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Data deleted successfully']);

        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);

        $this->getJson("/api/classroom/{$classroom->id}")
        ->assertStatus(404)
        ->assertJsonFragment([
            'error' => "Classroom with ID(s) {$classroom->id} not found.",
        ]);
    }

    public function test_classroom_store_validation_errors()
    {
        $response = $this->postJson('/api/classroom', []);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'grade' => ['The grade field is required.'],
            'room_number' => ['The room number field is required.'],
        ]);
    }


    public function test_classroom_store_invalid_data()
    {
        $payload = [
            'grade' => 1,
            'room_number' => 101,
            'total_students' => 'twenty',
            'teacher_id' => 999,
        ];

        $response = $this->postJson('/api/classroom', $payload);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'grade' => ['The grade field must be a string.'],
            'room_number' => ['The room number field must be a string.'],
            'total_students' => ['The total students field must be an integer.'],
            'teacher_id' => ['The selected teacher id is invalid.'],
        ]);
    }
}
