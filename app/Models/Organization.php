<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'subdomain', 'email', 'phone', 'logo', 'favicon',
        'plan_id', 'owner_id', 'subscription_status', 'trial_ends_at',
        'subscription_ends_at', 'max_students', 'max_instructors', 'max_courses',
        'api_key', 'settings', 'features', 'is_active', 'activated_at'
    ];

    protected $casts = [
        'settings' => 'array',
        'features' => 'array',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'activated_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // ========== العلاقات ==========
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function colleges()
    {
        return $this->hasMany(College::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ========== التوابع ==========
    public function isSubscriptionActive()
    {
        if ($this->subscription_status === 'suspended') {
            return false;
        }

        if ($this->subscription_status === 'trial') {
            return $this->trial_ends_at && $this->trial_ends_at->isFuture();
        }

        if ($this->subscription_status === 'active') {
            return $this->subscription_ends_at === null || $this->subscription_ends_at->isFuture();
        }

        return false;
    }

    public function canAddMoreStudents()
    {
        return $this->users()->where('role', 'student')->count() < $this->max_students;
    }

    public function canAddMoreInstructors()
    {
        return $this->users()->where('role', 'instructor')->count() < $this->max_instructors;
    }

    public function canAddMoreCourses()
    {
        return $this->courses()->count() < $this->max_courses;
    }

    public function getUsageStats()
    {
        return [
            'students' => $this->users()->where('role', 'student')->count(),
            'instructors' => $this->users()->where('role', 'instructor')->count(),
            'courses' => $this->courses()->count(),
            'students_limit' => $this->max_students,
            'instructors_limit' => $this->max_instructors,
            'courses_limit' => $this->max_courses,
            'students_percentage' => round(($this->users()->where('role', 'student')->count() / $this->max_students) * 100, 2),
        ];
    }

    public function generateApiKey()
    {
        $this->api_key = Str::random(32);
        $this->save();
        return $this->api_key;
    }

    protected static function booted()
    {
        static::creating(function ($organization) {
            if (empty($organization->api_key)) {
                $organization->api_key = Str::random(32);
            }
        });
    }
}
