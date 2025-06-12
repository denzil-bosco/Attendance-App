<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Models\Attendance;

class SyncAttendanceFromCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:sync-from-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all cached attendance records from Redis into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = "attendance:class:record";
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
    }
}
