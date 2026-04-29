<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // البحث عن المؤسسة (Multi-Tenancy)
        $organization = null;
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        if ($subdomain && $subdomain != 'localhost' && $subdomain != 'www' && $subdomain != '127.0.0.1') {
            $organization = Organization::where('subdomain', $subdomain)->first();
        }

        $user = User::create([
            'organization_id' => $organization ? $organization->id : null,
            'name' => $request->name,
            'student_id' => $request->student_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
             'role' => $request->role ?? 'student',  // <-- أضف هذا السطر
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

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

        return redirect()->route('dashboard');
    }
}
