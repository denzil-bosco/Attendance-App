<?php
namespace App\Jobs;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class RecordDailyAttendanceJob implements ShouldQueue
{

    public function handle()
    {
        try {
            $today = Carbon::now()->format('Y-m-d');
            $key = "attendance:class:record:$today";
            $data = json_decode(Redis::get($key), true);
            if (is_null($data)) return;
            foreach ($data as $record) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'class_id' => $record['class_id'],
                        'date' => $record['date'],
                    ],
                    [
                        'class_id' => $record['class_id'],
                        'status' => $record['status'] ?? 'ABSENT',
                        'check_in_time' => $record['checkin_time'] ?? null,
                        'absence_reason' => $record['reason'] ?? null,
                        'remarks' => $record['remarks'] ?? null,
                        'recorded_by' => $record['recorded_by'],
                    ]
                );
            }
            Redis::flushall();
        } catch (Exception $e) {
            Log::error("RecordDailyAttendanceJob failed: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }    }
    }
