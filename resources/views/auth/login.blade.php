@extends('layouts.auth')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="p-8">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">مرحباً بعودتك</h2>
        <p class="text-gray-500 text-sm mt-1">سجل دخولك باستخدام الرقم الجامعي وكلمة المرور</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert-message bg-green-50 text-green-700 border border-green-200">
            <i class="fas fa-check-circle ml-2"></i>
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-message bg-red-50 text-red-700 border border-red-200">
            <i class="fas fa-exclamation-circle ml-2"></i>
            @foreach ($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- 🔁 الرقم الجامعي (بدلاً من البريد الإلكتروني) -->
        <div class="input-group">
            <input type="text" name="student_id" id="student_id" placeholder=" " value="{{ old('student_id') }}" required autofocus>
            <label for="student_id">
                <i class="fas fa-id-card ml-1"></i> الرقم الجامعي <span class="text-red-500">*</span>
            </label>
        </div>

        <!-- Password -->
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder=" " required>
            <label for="password">
                <i class="fas fa-lock ml-1"></i> كلمة المرور <span class="text-red-500">*</span>
            </label>
            <button type="button" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    onclick="togglePassword()">
                <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                <span class="mr-2 text-sm text-gray-600">تذكرني</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-accent transition">
                    <i class="fas fa-key ml-1"></i> نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-auth">
            <i class="fas fa-sign-in-alt ml-2"></i>
            تسجيل الدخول
        </button>
    </form>

    <!-- Face Login -->
    <div class="text-center mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-3 bg-white text-gray-400">أو</span>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('face.verify') }}" class="btn-face">
                <i class="fas fa-face-smile text-lg"></i>
                تسجيل الدخول ببصمة الوجه
            </a>
        </div>
    </div>

    <!-- Register Link -->
    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <p class="text-sm text-gray-600">
            ليس لديك حساب؟
            <a href="{{ route('register') }}" class="text-primary font-semibold hover:text-accent transition">
                إنشاء حساب جديد
                <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </p>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
