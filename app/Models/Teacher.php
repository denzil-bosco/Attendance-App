<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Teacher extends Authenticatable
// class Teacher extends Model
{
	use HasFactory, Notifiable;

	protected $table = 'teachers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
    	'name',
    	'email',
    	'phone_number',
    	'status',
        'subject',
    	'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
    	'password',
    ];

    /**
     * Relationship: A teacher may have many classes.
     */
    public function classes(): HasMany
    {
    	return $this->hasMany(Classroom::class);
    }

    /**
     * Function to check if the status is active
     */
    public function isActive(): bool
    {
    	return $this->status === 'ACTIVE';
    }
}
