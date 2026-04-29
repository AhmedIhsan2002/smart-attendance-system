<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'slug', 'price_monthly', 'price_yearly',
        'max_students', 'max_instructors', 'max_courses', 'max_departments',
        'features', 'has_face_recognition', 'has_api_access', 'has_advanced_reports',
        'is_active', 'sort_order'
    ];

    protected $casts = [
        'features' => 'array',
        'has_face_recognition' => 'boolean',
        'has_api_access' => 'boolean',
        'has_advanced_reports' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getPriceMonthlyFormattedAttribute()
    {
        return '$' . number_format($this->price_monthly, 2);
    }

    public function getPriceYearlyFormattedAttribute()
    {
        return '$' . number_format($this->price_yearly, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
