<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classrooms';

    protected $fillable = [
    	'name',
        'grade',
        'room_number',
        'total_students',
        'teacher_id',
    ];

    /**
     * A classroom belongs to a teacher.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * A classroom has many students.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * A classroom has many attendance records.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }
}
