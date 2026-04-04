<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Attendance;

class HomeController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalCourses = Course::count();
        $todayAttendance = Attendance::whereDate('created_at', today())->count();

        return view('home', compact('totalStudents', 'totalCourses', 'todayAttendance'));
    }
}
