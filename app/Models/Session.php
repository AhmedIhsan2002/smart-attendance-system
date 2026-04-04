<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Session extends Model
{
  use HasFactory;

  // تحديد اسم الجدول الصحيح
    protected $table = 'lecture_sessions';

 protected $fillable = [
     'course_id', 'date', 'start_time', 'end_time', 'qr_code',
     'qr_expires_at', 'is_active', 'status', 'notes'
 ];

 protected $casts = [
     'date' => 'date',
     'qr_expires_at' => 'datetime',
 ];

 public function course()
 {
     return $this->belongsTo(Course::class);
 }

 public function attendances()
 {
     return $this->hasMany(Attendance::class);
 }

 // توليد QR كود للمحاضرة
 public function generateQrCode()
 {
     $this->qr_code = uniqid() . '_' . $this->id . '_' . now()->timestamp;
     $this->qr_expires_at = now()->addMinutes(15); // الصلاحية 15 دقيقة
     $this->save();

     return $this->qr_code;
 }

 // هل QR لا يزال صالحاً؟
 public function isQrValid()
 {
     return $this->qr_code && now()->lessThan($this->qr_expires_at);
 }
}
