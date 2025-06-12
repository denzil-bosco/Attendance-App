<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance_record';

    protected $fillable = [
        'student_id',
        'class_id',
        'date',
        'status',
        'check_in_time',
        'absence_reason',
        'remarks',
        'recorded_by',
    ];

    /**
     * The student this attendance record belongs to.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * The class this attendance record is associated with.
     */
    public function class()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    /**
     * The user (teacher/admin) who recorded the attendance.
     */
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
