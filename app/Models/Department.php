<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id', 'name', 'name_ar', 'code', 'description', 'head_name'
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
