<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Session;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InstructorController extends Controller
{
    // لوحة التحكم الرئيسية
    public function dashboard()
    {
        $instructor = Auth::user();

        // المواد التي يدرسها الدكتور
        $courses = Course::where('instructor_id', $instructor->id)
                         ->where('is_active', true)
                         ->withCount('students')
                         ->get();

        // إحصائيات عامة
        $totalCourses = $courses->count();
        $totalStudents = $courses->sum('students_count');

        // جلسات اليوم
        $todaySessions = Session::whereHas('course', function($q) use ($instructor) {
                                    $q->where('instructor_id', $instructor->id);
                                })
                                ->where('date', Carbon::today())
                                ->with('course')
                                ->orderBy('start_time')
                                ->get();

        // الجلسات القادمة (الأسبوع القادم)
        $upcomingSessions = Session::whereHas('course', function($q) use ($instructor) {
                                        $q->where('instructor_id', $instructor->id);
                                    })
                                    ->where('date', '>', Carbon::today())
                                    ->where('date', '<=', Carbon::today()->addDays(7))
                                    ->with('course')
                                    ->orderBy('date')
                                    ->orderBy('start_time')
                                    ->take(5)
                                    ->get();

        // آخر 5 تسجيلات حضور
        $recentAttendances = Attendance::whereHas('session.course', function($q) use ($instructor) {
                                            $q->where('instructor_id', $instructor->id);
                                        })
                                        ->with(['user', 'session.course'])
                                        ->orderBy('created_at', 'desc')
                                        ->take(10)
                                        ->get();

        // إحصائيات الحضور اليوم
        $todayAttendanceCount = Attendance::whereHas('session.course', function($q) use ($instructor) {
                                                $q->where('instructor_id', $instructor->id);
                                            })
                                            ->whereDate('created_at', Carbon::today())
                                            ->count();

        return view('instructor.dashboard', compact(
            'instructor', 'courses', 'totalCourses', 'totalStudents',
            'todaySessions', 'upcomingSessions', 'recentAttendances',
            'todayAttendanceCount'
        ));
    }

    // عرض المواد
    public function courses()
    {
        $instructor = Auth::user();
        $courses = Course::where('instructor_id', $instructor->id)
                         ->withCount('students')
                         ->withCount('sessions')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('instructor.courses', compact('courses'));
    }

    // عرض مادة معينة
    public function showCourse($id)
    {
        $instructor = Auth::user();
        $course = Course::where('instructor_id', $instructor->id)
                        ->with(['students', 'sessions' => function($q) {
                            $q->orderBy('date', 'desc')->take(10);
                        }])
                        ->findOrFail($id);

        // إحصائيات حضور الطلاب في هذه المادة
        $studentsAttendance = [];
        foreach ($course->students as $student) {
            $totalSessions = Session::where('course_id', $course->id)
                                    ->where('date', '<=', Carbon::today())
                                    ->count();

            $presentCount = Attendance::where('user_id', $student->id)
                                      ->whereHas('session', function($q) use ($course) {
                                          $q->where('course_id', $course->id);
                                      })
                                      ->where('status', 'present')
                                      ->count();

            $lateCount = Attendance::where('user_id', $student->id)
                                   ->whereHas('session', function($q) use ($course) {
                                       $q->where('course_id', $course->id);
                                   })
                                   ->where('status', 'late')
                                   ->count();

            $attendedCount = $presentCount + $lateCount;
            $percentage = $totalSessions > 0 ? round(($attendedCount / $totalSessions) * 100, 1) : 0;

            $studentsAttendance[$student->id] = [
                'student' => $student,
                'total_sessions' => $totalSessions,
                'attended' => $attendedCount,
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $totalSessions - $attendedCount,
                'percentage' => $percentage
            ];
        }

        return view('instructor.course-detail', compact('course', 'studentsAttendance'));
    }

    // بدء محاضرة جديدة (توليد QR Code)
    public function startSession($courseId)
    {
        $instructor = Auth::user();
        $course = Course::where('instructor_id', $instructor->id)->findOrFail($courseId);

        // التحقق من وجود محاضرة نشطة لهذه المادة اليوم
        $existingSession = Session::where('course_id', $courseId)
                                  ->where('date', Carbon::today())
                                  ->where('status', 'ongoing')
                                  ->first();

        if ($existingSession) {
            return redirect()->back()->with('error', 'يوجد محاضرة نشطة لهذه المادة اليوم بالفعل');
        }

        // إنشاء محاضرة جديدة
        $session = Session::create([
            'course_id' => $courseId,
            'date' => Carbon::today(),
            'start_time' => Carbon::now()->format('H:i:s'),
            'end_time' => Carbon::now()->addHours(2)->format('H:i:s'),
            'qr_code' => 'QR_' . uniqid() . '_' . time(),
            'qr_expires_at' => Carbon::now()->addMinutes(15),
            'status' => 'ongoing',
            'is_active' => true
        ]);

        // توليد QR Code
        $qrCode = QrCode::size(300)->generate(route('attendance.qr', $session->qr_code));

        return view('instructor.session-qr', compact('session', 'course', 'qrCode'));
    }

    // عرض جلسات المادة
    public function sessions($courseId)
    {
        $instructor = Auth::user();
        $course = Course::where('instructor_id', $instructor->id)->findOrFail($courseId);

        $sessions = Session::where('course_id', $courseId)
                           ->withCount('attendances')
                           ->orderBy('date', 'desc')
                           ->orderBy('start_time', 'desc')
                           ->paginate(20);

        return view('instructor.sessions', compact('course', 'sessions'));
    }

    // عرض تفاصيل جلسة معينة
    public function showSession($sessionId)
    {
        $instructor = Auth::user();
        $session = Session::with(['course', 'attendances.user'])
                          ->whereHas('course', function($q) use ($instructor) {
                              $q->where('instructor_id', $instructor->id);
                          })
                          ->findOrFail($sessionId);

        $attendances = $session->attendances;

        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $absentCount = $session->course->students()->count() - ($presentCount + $lateCount);

        return view('instructor.session-detail', compact('session', 'attendances', 'presentCount', 'lateCount', 'absentCount'));
    }

    // إنهاء محاضرة
    public function endSession($sessionId)
    {
        $instructor = Auth::user();
        $session = Session::whereHas('course', function($q) use ($instructor) {
                                $q->where('instructor_id', $instructor->id);
                            })
                            ->findOrFail($sessionId);

        $session->update([
            'status' => 'completed',
            'is_active' => false
        ]);

        return redirect()->route('instructor.course.sessions', $session->course_id)
                         ->with('success', 'تم إنهاء المحاضرة بنجاح');
    }

    // عرض حضور الطلاب في الوقت الفعلي
    public function liveAttendance($courseId)
    {
        $instructor = Auth::user();
        $course = Course::where('instructor_id', $instructor->id)->findOrFail($courseId);

        $todaySession = Session::where('course_id', $courseId)
                               ->where('date', Carbon::today())
                               ->where('status', 'ongoing')
                               ->first();

        if (!$todaySession) {
            return redirect()->route('instructor.courses')->with('error', 'لا توجد محاضرة نشطة لهذه المادة اليوم');
        }

        $attendances = Attendance::where('session_id', $todaySession->id)
                                 ->with('user')
                                 ->get();

        $attendedStudents = $attendances->pluck('user_id')->toArray();

        $allStudents = $course->students;

        return view('instructor.live-attendance', compact('course', 'todaySession', 'attendances', 'attendedStudents', 'allStudents'));
    }

    // تسجيل حضور يدوي للطالب
    public function manualAttendance(Request $request, $sessionId)
    {
        $instructor = Auth::user();
        $session = Session::whereHas('course', function($q) use ($instructor) {
                                $q->where('instructor_id', $instructor->id);
                            })
                            ->findOrFail($sessionId);

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'status' => 'required|in:present,late,excused'
        ]);

        $existingAttendance = Attendance::where('session_id', $sessionId)
                                        ->where('user_id', $request->student_id)
                                        ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'الطالب مسجل حضوره بالفعل');
        }

        Attendance::create([
            'user_id' => $request->student_id,
            'session_id' => $sessionId,
            'status' => $request->status,
            'check_in_time' => now(),
            'verification_method' => 'manual',
            'is_verified' => true
        ]);

        return redirect()->back()->with('success', 'تم تسجيل حضور الطالب بنجاح');
    }
}
