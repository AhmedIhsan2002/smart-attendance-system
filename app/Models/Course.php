<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'code', 'description', 'instructor_id',
        'department_id', 'credit_hours', 'semester', 'start_time',
        'end_time', 'location', 'max_students', 'is_active'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
                    ->withPivot('grade', 'status')
                    ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
