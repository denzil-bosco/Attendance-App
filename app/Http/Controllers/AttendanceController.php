<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class AttendanceController extends Controller
{
	public function store(Request $request)
	{
		try {

			$today = Carbon::today();
			$student = Student::findOrFail($request->student_id);
			$teacherId = $request->teacher_id;
			$classId = $request->class_id;

			$today = Carbon::now()->format('Y-m-d');
			$curTime = Carbon::now()->format('H:i:s');
			$key = "attendance:class:record:$today";
			$jsonData = Redis::get($key);
			$attendanceArray = $jsonData ? json_decode($jsonData, true) : [];

			$newRecord = [
				'student_id' => $student->id,
				'class_id' => $classId,
				'date' => $today,
				'checkin_time' => $curTime,
				'reason' => $request->reason,
				'status' => $request->status ?? 'PRESENT',
				'recorded_by' => $teacherId ?? 1,
			];
			$attendanceArray[] = $newRecord;
			Redis::set($key, json_encode($attendanceArray));
			return response()->json(['message' => 'Attendance cached successfully'], 200);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'error' => 'Record not found'
			], 404);
		}
	}
}
