@extends('layouts.auth')

@section('title', 'تسجيل الدخول - طلاب ودكاترة')

@section('content')
<div class="p-8">
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-user-graduate text-white text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">تسجيل دخول الطلاب والدكاترة</h2>
        <p class="text-gray-500 text-sm mt-1">استخدم الرقم الجامعي وكلمة المرور</p>
    </div>

    @if ($errors->any())
        <div class="alert-message bg-red-50 text-red-700 border border-red-200 mb-4 p-3 rounded-lg">
            @foreach ($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.educational.submit') }}">
        @csrf

        <!-- الرقم الجامعي -->
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
                <input type="checkbox" name="remember" class="w-4 h-4 text-primary rounded">
                <span class="mr-2 text-sm text-gray-600">تذكرني</span>
            </label>
        </div>

        <button type="submit" class="btn-auth">
            <i class="fas fa-sign-in-alt ml-2"></i>
            تسجيل الدخول
        </button>
    </form>

    <div class="text-center mt-6">
        <p class="text-sm text-gray-600">
            تسجيل دخول الإداريين؟
            <a href="{{ route('login.admin') }}" class="text-primary font-semibold">
                اضغط هنا
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
