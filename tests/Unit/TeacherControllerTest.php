<?php

namespace Tests\Feature;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_teachers()
    {
        $teachers = Teacher::factory()->count(3)->create();

        $response = $this->getJson('/api/teacher');

        $response->assertStatus(200);

        foreach ($teachers as $teacher) {
            $response->assertJsonFragment([
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'subject' => $teacher->subject,
                'status' => $teacher->status,
            ]);
        }
    }

    public function test_store_creates_new_teacher()
    {
        $payload = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone_number' => '1234567890',
            'subject' => 'Math',
            'status' => 'ACTIVE',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/api/teacher', $payload);

        $response->assertStatus(201)
        ->assertJsonFragment([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone_number' => '1234567890',
            'subject' => 'Math',
            'status' => 'ACTIVE',
        ]);

        unset($payload['password']);
        $this->assertDatabaseHas('teachers', $payload);
    }

    public function test_show_returns_teacher_data()
    {
        $teacher = Teacher::factory()->create();

        $this->getJson("/api/teacher/{$teacher->id}")
        ->assertStatus(200)
        ->assertJsonFragment([
            'id' => $teacher->id,
            'name' => $teacher->name,
            'email' => $teacher->email,
            'phone_number' => $teacher->phone_number,
            'subject' => $teacher->subject,
            'status' => $teacher->status,
        ]);

        $this->getJson("/api/teacher/999")
        ->assertStatus(404)
        ->assertJsonFragment([
            'error' => "Teacher with ID(s) 999 not found.",
        ]);
    }

    public function test_update_modifies_teacher_data()
    {
        $teacher = Teacher::factory()->create();

        $updatePayload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $this->putJson("/api/teacher/{$teacher->id}", $updatePayload)
        ->assertStatus(200)
        ->assertJsonFragment($updatePayload);

        $this->assertDatabaseHas('teachers', $updatePayload);

        $this->putJson("/api/teacher/999")
        ->assertStatus(404)
        ->assertJsonFragment([
            'error' => "Teacher with ID(s) 999 not found.",
        ]);
    }

    public function test_destroy_deletes_teacher()
    {
        $teacher = Teacher::factory()->create();

        $this->deleteJson("/api/teacher/{$teacher->id}")
        ->assertStatus(204);

        $this->assertDatabaseMissing('teachers', ['id' => $teacher->id]);

        $this->putJson("/api/teacher/{$teacher->id}")
        ->assertStatus(404)
        ->assertJsonFragment([
            'error' => "Teacher with ID(s) {$teacher->id} not found.",
        ]);
    }

    public function test_teacher_store_validation_errors()
    {
        $response = $this->postJson('/api/teacher', []);

        $response->assertStatus(422)
        ->assertJsonFragment([
            'email' => ['The email field is required.'],
            'name' => ['The name field is required.'],
            'password' => ['The password field is required.'],
            'subject' => ['The subject field is required.'],
        ]);
    }

    public function test_teacher_store_invalid_values()
    {
        $payload = [
            'name' => 1,
            'phone_number' => 1234567890,
            'subject' => 2,
            'status' => 'NEW',
            'email' => 'not-an-email',
            'password' => '1234',
        ];

        $response = $this->postJson('/api/teacher', $payload);

        $response->assertJsonFragment([
            'name' => ['The name field must be a string.'],
            'email' => ['The email field must be a valid email address.'],
            'phone_number' => ['The phone number field must be a string.'],
            'status' => ['The selected status is invalid.'],
            'subject' => ['The subject field must be a string.'],
            'password' => ['The password field must be at least 6 characters.'],
        ]);
    }
}
