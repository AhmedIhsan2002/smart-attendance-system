<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    // صفحة التقارير الرئيسية
    public function index(Request $request)
    {
        $user = Auth::user();

        // جلب المواد حسب دور المستخدم
        if ($user->role == 'student') {
            $courses = $user->courses()->where('is_active', true)->get();
        } elseif ($user->role == 'instructor') {
            $courses = Course::where('instructor_id', $user->id)->where('is_active', true)->get();
        } else {
            $courses = Course::where('is_active', true)->get();
        }

        // فلترة حسب المادة
        $selectedCourse = $request->course_id;
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now()->format('Y-m-d');

        // إحصائيات عامة
        $stats = $this->getStatistics($user, $selectedCourse, $dateFrom, $dateTo);

        // بيانات الرسم البياني
        $chartData = $this->getChartData($user, $selectedCourse, $dateFrom, $dateTo);

        // بيانات الجدول
        $attendanceData = $this->getAttendanceTable($user, $selectedCourse, $dateFrom, $dateTo);

        return view('reports', compact('courses', 'selectedCourse', 'dateFrom', 'dateTo', 'stats', 'chartData', 'attendanceData'));
    }

    // إحصائيات عامة
    private function getStatistics($user, $courseId, $dateFrom, $dateTo)
    {
        $query = Attendance::whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($user->role == 'student') {
            $query->where('user_id', $user->id);
        } elseif ($user->role == 'instructor') {
            $query->whereHas('session.course', function($q) use ($user) {
                $q->where('instructor_id', $user->id);
            });
        }

        if ($courseId) {
            $query->whereHas('session', function($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        $totalAttendance = $query->count();
        $presentCount = $query->where('status', 'present')->count();
        $lateCount = $query->where('status', 'late')->count();
        $absentCount = $query->where('status', 'absent')->count();

        return [
            'total' => $totalAttendance,
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'present_percentage' => $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 1) : 0,
            'late_percentage' => $totalAttendance > 0 ? round(($lateCount / $totalAttendance) * 100, 1) : 0,
            'absent_percentage' => $totalAttendance > 0 ? round(($absentCount / $totalAttendance) * 100, 1) : 0,
        ];
    }

    // بيانات الرسم البياني
    private function getChartData($user, $courseId, $dateFrom, $dateTo)
    {
        $dates = [];
        $presentData = [];
        $lateData = [];
        $absentData = [];

        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $currentDate = $date->format('Y-m-d');
            $dates[] = $currentDate;

            $query = Attendance::whereDate('check_in_time', $currentDate);

            if ($user->role == 'student') {
                $query->where('user_id', $user->id);
            } elseif ($user->role == 'instructor') {
                $query->whereHas('session.course', function($q) use ($user) {
                    $q->where('instructor_id', $user->id);
                });
            }

            if ($courseId) {
                $query->whereHas('session', function($q) use ($courseId) {
                    $q->where('course_id', $courseId);
                });
            }

            $presentData[] = $query->where('status', 'present')->count();
            $lateData[] = $query->where('status', 'late')->count();
            $absentData[] = $query->where('status', 'absent')->count();
        }

        return [
            'dates' => $dates,
            'present' => $presentData,
            'late' => $lateData,
            'absent' => $absentData,
        ];
    }

    // بيانات جدول الحضور
    private function getAttendanceTable($user, $courseId, $dateFrom, $dateTo)
    {
        $query = Attendance::with(['user', 'session.course'])
                          ->whereBetween('check_in_time', [$dateFrom, $dateTo . ' 23:59:59'])
                          ->orderBy('check_in_time', 'desc');

        if ($user->role == 'student') {
            $query->where('user_id', $user->id);
        } elseif ($user->role == 'instructor') {
            $query->whereHas('session.course', function($q) use ($user) {
                $q->where('instructor_id', $user->id);
            });
        }

        if ($courseId) {
            $query->whereHas('session', function($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        return $query->paginate(20);
    }

    // إحصائيات المواد للدكتور
    public function courseStatistics($courseId)
    {
        $user = Auth::user();

        // التأكد من أن الدكتور يدرس هذه المادة
        if ($user->role == 'instructor') {
            $course = Course::where('instructor_id', $user->id)->findOrFail($courseId);
        } else {
            $course = Course::findOrFail($courseId);
        }

        $students = $course->students;
        $totalSessions = Session::where('course_id', $courseId)
                               ->where('date', '<=', Carbon::today())
                               ->count();

        $studentsData = [];
        foreach ($students as $student) {
            $presentCount = Attendance::where('user_id', $student->id)
                                      ->whereHas('session', function($q) use ($courseId) {
                                          $q->where('course_id', $courseId);
                                      })
                                      ->where('status', 'present')
                                      ->count();

            $lateCount = Attendance::where('user_id', $student->id)
                                   ->whereHas('session', function($q) use ($courseId) {
                                       $q->where('course_id', $courseId);
                                   })
                                   ->where('status', 'late')
                                   ->count();

            $attendedCount = $presentCount + $lateCount;
            $percentage = $totalSessions > 0 ? round(($attendedCount / $totalSessions) * 100, 1) : 0;

            $studentsData[] = [
                'student' => $student,
                'present' => $presentCount,
                'late' => $lateCount,
                'absent' => $totalSessions - $attendedCount,
                'percentage' => $percentage,
                'status' => $percentage >= 75 ? 'ملتزم' : ($percentage >= 50 ? 'تحذير' : 'إنذار')
            ];
        }

        return view('reports.course-statistics', compact('course', 'studentsData', 'totalSessions'));
    }

    // تصدير تقرير PDF
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now()->format('Y-m-d');
        $courseId = $request->course_id;

        $stats = $this->getStatistics($user, $courseId, $dateFrom, $dateTo);
        $attendanceData = $this->getAttendanceTable($user, $courseId, $dateFrom, $dateTo);

        $pdf = PDF::loadView('reports.pdf', compact('stats', 'attendanceData', 'dateFrom', 'dateTo'));

        return $pdf->download('attendance-report-' . date('Y-m-d') . '.pdf');
    }

    // تصدير تقرير Excel
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::now()->format('Y-m-d');
        $courseId = $request->course_id;

        $attendanceData = $this->getAttendanceTable($user, $courseId, $dateFrom, $dateTo);

        // إنشاء ملف Excel
        $fileName = 'attendance-report-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        // إضافة headers
        fputcsv($handle, ['التاريخ', 'الطالب', 'المادة', 'الحالة', 'وقت التسجيل', 'طريقة التسجيل']);

        // إضافة البيانات
        foreach ($attendanceData as $attendance) {
            fputcsv($handle, [
                $attendance->check_in_time->format('Y-m-d'),
                $attendance->user->name,
                $attendance->session->course->name_ar ?? $attendance->session->course->name,
                $attendance->status == 'present' ? 'حاضر' : ($attendance->status == 'late' ? 'متأخر' : 'غائب'),
                $attendance->check_in_time->format('H:i:s'),
                $attendance->verification_method == 'qr' ? 'QR Code' : ($attendance->verification_method == 'face' ? 'بصمة وجه' : 'يدوي')
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }
}
