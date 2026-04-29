<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'student_id', 'university_id',
        'role', 'profile_photo', 'face_encoding', 'is_active',
        'phone', 'gender', 'birth_date', 'address', 'department', 'enrollment_year'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'birth_date' => 'date',
        ];
    }

    // العلاقات
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
                    ->withPivot('grade', 'status')
                    ->withTimestamps();
    }

    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // أضف هذه العلاقات
public function organization()
{
    return $this->belongsTo(Organization::class);
}

public function ownedOrganizations()
{
    return $this->hasMany(Organization::class, 'owner_id');
}

public function isSuperAdmin()
{
    return $this->role === 'super_admin';
}

public function isOrganizationAdmin()
{
    return $this->role === 'admin';
}

// Scope للحدود حسب المؤسسة
public function scopeForOrganization($query, $organizationId)
{
    return $query->where('organization_id', $organizationId);
}
}
