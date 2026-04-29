<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationalLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'student_id' => $request->student_id,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // فقط الطلاب والدكاترة
            if ($user->role == 'student') {
                return redirect()->route('student.dashboard');
            } elseif ($user->role == 'instructor') {
                return redirect()->route('instructor.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'student_id' => 'هذا الحساب ليس طالباً أو دكتوراً. استخدم صفحة تسجيل دخول الإداريين.',
                ]);
            }
        }

        return back()->withErrors([
            'student_id' => 'الرقم الجامعي أو كلمة المرور غير صحيحة.',
        ])->onlyInput('student_id');
    }
}
