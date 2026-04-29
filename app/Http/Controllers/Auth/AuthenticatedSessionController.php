<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'student_id' => 'required|string',
        'password' => 'required|string',
    ]);

    // محاولة تسجيل الدخول باستخدام الرقم الجامعي
    $credentials = [
        'student_id' => $request->student_id,
        'password' => $request->password,
    ];

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        // التوجيه حسب الدور
        if ($user->role == 'super_admin') {
            return redirect()->route('super.dashboard');
        } elseif ($user->role == 'student') {
            return redirect()->route('student.dashboard');
        } elseif ($user->role == 'instructor') {
            return redirect()->route('instructor.dashboard');
        } elseif ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors([
        'student_id' => 'الرقم الجامعي أو كلمة المرور غير صحيحة.',
    ])->onlyInput('student_id');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
