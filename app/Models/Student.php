<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	use HasFactory;
	protected $table = 'students';


	protected $fillable = [
		'first_name',
		'last_name',
		'date_of_birth',
		'gender',
		'contact_person_name',
		'contact_person_phone',
		'enrollment_date',
		'student_id_number',
		'class_id',
	];

    /**
     * A student belongs to a class.
     */
    public function class()
    {
    	return $this->belongsTo(Classroom::class, 'class_id');
    }

    /**
     * A student has many attendance records.
     */
    public function attendances()
    {
    	return $this->hasMany(Attendance::class);
    }

    /**
     * Accessor to get full name.
     */
    public function getFullNameAttribute()
    {
    	return "{$this->first_name} {$this->last_name}";
    }
}
