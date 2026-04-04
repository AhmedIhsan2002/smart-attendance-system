<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'description', 'code', 'dean_name',
        'phone', 'email', 'building'
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
