<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // التحقق من QR Code وتسجيل الحضور
    public function verifyQr($qrCode)
    {
        $user = Auth::user();

        // البحث عن الجلسة التي تحمل هذا QR Code
        $session = Session::where('qr_code', $qrCode)
                          ->where('qr_expires_at', '>', Carbon::now())
                          ->where('is_active', true)
                          ->first();

        if (!$session) {
            return redirect()->route('attendance')->with('error', 'رمز QR غير صالح أو منتهي الصلاحية');
        }

        // التحقق من أن الطالب مسجل في هذه المادة
        $isEnrolled = $user->courses()->where('course_id', $session->course_id)->exists();

        if (!$isEnrolled) {
            return redirect()->route('attendance')->with('error', 'أنت غير مسجل في هذه المادة');
        }

        // التحقق من أن الطالب لم يسجل حضوره بالفعل لهذه المحاضرة
        $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->where('session_id', $session->id)
                                        ->first();

        if ($existingAttendance) {
            return redirect()->route('attendance')->with('warning', 'لقد سجلت حضورك بالفعل لهذه المحاضرة');
        }

        // تحديد حالة الحضور (حاضر أم متأخر)
        $sessionStartTime = $session->start_time;
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime > $sessionStartTime) {
            $status = 'late';
            $message = 'تم تسجيل حضورك بنجاح (متأخر)';
        } else {
            $status = 'present';
            $message = 'تم تسجيل حضورك بنجاح';
        }

        // تسجيل الحضور
        Attendance::create([
            'user_id' => $user->id,
            'session_id' => $session->id,
            'status' => $status,
            'check_in_time' => Carbon::now(),
            'verification_method' => 'qr',
            'is_verified' => true,
            'ip_address' => request()->ip(),
            'device_info' => request()->userAgent()
        ]);

        return redirect()->route('attendance')->with('success', $message);
    }

    // عرض صفحة تسجيل الحضور مع الخيارات
    public function index()
    {
        $user = Auth::user();

        // جلب محاضرات اليوم للطالب
        $todaySessions = Session::where('date', Carbon::today())
                                ->whereHas('course', function($q) use ($user) {
                                    $q->whereHas('students', function($q2) use ($user) {
                                        $q2->where('user_id', $user->id);
                                    });
                                })
                                ->with('course')
                                ->get();

        return view('attendance', compact('todaySessions'));
    }

    // تسجيل حضور يدوي (للتجربة)
    public function manualAttendance(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:sessions,id',
        ]);

        $user = Auth::user();
        $session = Session::find($request->session_id);

        // التحقق من صحة الجلسة
        if (!$session || $session->date != Carbon::today()) {
            return redirect()->back()->with('error', 'محاضرة غير صالحة');
        }

        // التحقق من التسجيل المسبق
        $existingAttendance = Attendance::where('user_id', $user->id)
                                        ->where('session_id', $session->id)
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('warning', 'لقد سجلت حضورك بالفعل');
        }

        // تسجيل الحضور
        Attendance::create([
            'user_id' => $user->id,
            'session_id' => $session->id,
            'status' => 'present',
            'check_in_time' => Carbon::now(),
            'verification_method' => 'manual',
            'is_verified' => true
        ]);

        return redirect()->back()->with('success', 'تم تسجيل حضورك بنجاح');
    }

    // إدخال QR Code يدوياً
public function manualQrInput(Request $request)
{
    $request->validate([
        'qr_code' => 'required|string'
    ]);

    return $this->verifyQr($request->qr_code);
}
}
