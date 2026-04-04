<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Session;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // جلب المواد التي يدرسها الطالب
        $courses = $user->courses()->where('is_active', true)->get();

        // إحصائيات الحضور
        $totalSessions = 0;
        $presentCount = 0;
        $lateCount = 0;
        $absentCount = 0;

        $attendanceStats = [];

        foreach ($courses as $course) {
            // جلسات هذه المادة
            $sessions = Session::where('course_id', $course->id)
                               ->where('date', '<=', today())
                               ->count();

            // حضور الطالب في هذه المادة
            $present = Attendance::where('user_id', $user->id)
                                 ->whereHas('session', function($q) use ($course) {
                                     $q->where('course_id', $course->id);
                                 })
                                 ->where('status', 'present')
                                 ->count();

            $late = Attendance::where('user_id', $user->id)
                              ->whereHas('session', function($q) use ($course) {
                                  $q->where('course_id', $course->id);
                              })
                              ->where('status', 'late')
                              ->count();

            $absent = $sessions - ($present + $late);

            $totalSessions += $sessions;
            $presentCount += $present;
            $lateCount += $late;
            $absentCount += $absent;

            // حساب النسبة المئوية للحضور لكل مادة
            $percentage = $sessions > 0 ? round((($present + $late) / $sessions) * 100, 1) : 0;

            $attendanceStats[$course->id] = [
                'course_name' => $course->name_ar ?? $course->name,
                'total_sessions' => $sessions,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'percentage' => $percentage,
                'warning' => $percentage < 75 // تنبيه إذا كان الحضور أقل من 75%
            ];
        }

        // حساب النسبة الإجمالية
        $totalPercentage = $totalSessions > 0 ? round((($presentCount + $lateCount) / $totalSessions) * 100, 1) : 0;

        // آخر 5 إشعارات
        $notifications = $user->notifications()
                              ->orderBy('created_at', 'desc')
                              ->take(5)
                              ->get();

        // جلسات اليوم (المحاضرات القادمة)
        $todaySessions = Session::where('date', today())
                                ->whereHas('course', function($q) use ($user) {
                                    $q->whereHas('students', function($q2) use ($user) {
                                        $q2->where('user_id', $user->id);
                                    });
                                })
                                ->with('course')
                                ->orderBy('start_time')
                                ->get();

        // آخر 5 تسجيلات حضور
        $recentAttendances = Attendance::where('user_id', $user->id)
                                       ->with('session.course')
                                       ->orderBy('created_at', 'desc')
                                       ->take(5)
                                       ->get();

        return view('student.dashboard', compact(
            'user', 'courses', 'attendanceStats', 'totalPercentage',
            'notifications', 'todaySessions', 'recentAttendances',
            'presentCount', 'lateCount', 'absentCount', 'totalSessions'
        ));
    }

    public function attendanceHistory()
    {
        $user = Auth::user();

        $attendances = Attendance::where('user_id', $user->id)
                                 ->with('session.course')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(15);

        return view('student.attendance-history', compact('attendances'));
    }

    public function courseAttendance($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        // التأكد من أن الطالب مسجل في هذه المادة
        if (!$user->courses->contains($courseId)) {
            abort(403, 'غير مصرح لك بمشاهدة هذه المادة');
        }

        $attendances = Attendance::where('user_id', $user->id)
                                 ->whereHas('session', function($q) use ($courseId) {
                                     $q->where('course_id', $courseId);
                                 })
                                 ->with('session')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        $sessions = Session::where('course_id', $courseId)
                           ->where('date', '<=', today())
                           ->orderBy('date', 'desc')
                           ->get();

        return view('student.course-attendance', compact('course', 'attendances', 'sessions'));
    }
}
