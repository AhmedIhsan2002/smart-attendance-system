<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  // <-- هذا السطر مهم جداً

class Attendance extends Model
{
    use HasFactory;  // الآن سيعمل بشكل صحيح

    protected $fillable = [
        'user_id', 'session_id', 'status', 'check_in_time',
        'check_in_photo', 'latitude', 'longitude', 'ip_address',
        'device_info', 'verification_method', 'is_verified', 'notes'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    // تسجيل الحضور
    public static function recordAttendance($userId, $sessionId, $method, $location = null)
    {
        $attendance = new self();
        $attendance->user_id = $userId;
        $attendance->session_id = $sessionId;
        $attendance->check_in_time = now();
        $attendance->verification_method = $method;
        $attendance->is_verified = true;

        if ($location) {
            $attendance->latitude = $location['latitude'] ?? null;
            $attendance->longitude = $location['longitude'] ?? null;
        }

        // تحديد الحالة (حاضر أو متأخر)
        $session = Session::find($sessionId);
        if ($session) {
            $sessionStartTime = $session->start_time;
            $checkInTime = now()->format('H:i:s');

            if ($checkInTime > $sessionStartTime) {
                $attendance->status = 'late';
            } else {
                $attendance->status = 'present';
            }
        }

        $attendance->save();
        return $attendance;
    }
}
