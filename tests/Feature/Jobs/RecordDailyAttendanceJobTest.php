<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Jobs\RecordDailyAttendanceJob;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class RecordDailyAttendanceJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_processes_attendance_records_from_redis()
    {
        $this->assertDatabaseCount('attendance_record', 0);
        $student = Student::factory()->create();
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();

        $data = [
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'class_id' => $classroom->id,
            'reason' => 'Late arrival',
            'status' => 'PRESENT',
        ];

        $response = $this->postJson('/api/mark/attendance', $data);

        $response->assertStatus(200)
        ->assertJson(['message' => 'Attendance cached successfully']);
        $today = Carbon::now()->format('Y-m-d');
        $job = new RecordDailyAttendanceJob();
        $job->handle();

        $this->assertDatabaseCount('attendance_record', 1);
        $this->assertDatabaseHas('attendance_record', [
            'student_id' => $student->id,
            'class_id' => $classroom->id,
            'date' => $today,
            'status' => 'PRESENT',
            'absence_reason' => 'Late arrival',
            'remarks' => null,
            'recorded_by' => $teacher->id,
        ]);
    }

    public function test_it_does_nothing_when_redis_data_is_empty()
    {
        $today = Carbon::now()->format('Y-m-d');
        $key = "attendance:class:record:$today";

        $response = $this->postJson('/api/mark/attendance', []);

        $this->assertDatabaseCount('attendance_record', 0);

        $job = new RecordDailyAttendanceJob();
        $job->handle();

        $this->assertDatabaseCount('attendance_record', 0);
    }
}
