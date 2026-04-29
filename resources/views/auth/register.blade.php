@extends('layouts.auth')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div class="p-8">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">إنشاء حساب جديد</h2>
        <p class="text-gray-500 text-sm mt-1">انضم إلينا وابدأ رحلتك التعليمية</p>
    </div>

    @if ($errors->any())
        <div class="alert-message bg-red-50 text-red-700 border border-red-200">
            <i class="fas fa-exclamation-circle ml-2"></i>
            @foreach ($errors->all() as $error)
                <p class="text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- الاسم الكامل (مطلوب) -->
        <div class="input-group">
            <input type="text" name="name" id="name" placeholder=" " value="{{ old('name') }}" required autofocus>
            <label for="name">
                <i class="fas fa-user ml-1"></i> الاسم الكامل <span class="text-red-500">*</span>
            </label>
        </div>

        <!-- الرقم الجامعي (مطلوب - سيستخدم لتسجيل الدخول) -->
        <div class="input-group">
            <input type="text" name="student_id" id="student_id" placeholder=" " value="{{ old('student_id') }}" required>
            <label for="student_id">
                <i class="fas fa-id-card ml-1"></i> الرقم الجامعي <span class="text-red-500">*</span>
            </label>
            <p class="text-xs text-gray-500 mt-1">سيستخدم هذا الرقم لتسجيل الدخول إلى النظام</p>
        </div>

        <!-- Role Selection -->
<div class="input-group">
    <select name="role" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>طالب</option>
        <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>دكتور/محاضر</option>
        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>أدمن مؤسسة</option>
    </select>
    <label for="role">
        <i class="fas fa-user-tag ml-1"></i> نوع الحساب <span class="text-red-500">*</span>
    </label>
</div>

        <!-- البريد الإلكتروني (مطلوب) -->
        <div class="input-group">
            <input type="email" name="email" id="email" placeholder=" " value="{{ old('email') }}" required>
            <label for="email">
                <i class="fas fa-envelope ml-1"></i> البريد الإلكتروني <span class="text-red-500">*</span>
            </label>
        </div>

        <!-- رقم الهاتف (اختياري) -->
        <div class="input-group">
            <input type="tel" name="phone" id="phone" placeholder=" " value="{{ old('phone') }}">
            <label for="phone">
                <i class="fas fa-phone ml-1"></i> رقم الهاتف
            </label>
        </div>

        <!-- كلمة المرور (مطلوبة) -->
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder=" " required>
            <label for="password">
                <i class="fas fa-lock ml-1"></i> كلمة المرور <span class="text-red-500">*</span>
            </label>
            <button type="button" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    onclick="togglePassword('password', 'toggleIcon1')">
                <i class="fas fa-eye" id="toggleIcon1"></i>
            </button>
        </div>

        <!-- تأكيد كلمة المرور (مطلوب) -->
        <div class="input-group">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" " required>
            <label for="password_confirmation">
                <i class="fas fa-check-circle ml-1"></i> تأكيد كلمة المرور <span class="text-red-500">*</span>
            </label>
            <button type="button" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                    onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                <i class="fas fa-eye" id="toggleIcon2"></i>
            </button>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-auth mt-4">
            <i class="fas fa-user-plus ml-2"></i>
            إنشاء حساب
        </button>
    </form>

    <!-- Login Link -->
    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <p class="text-sm text-gray-600">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="text-primary font-semibold hover:text-accent transition">
                تسجيل الدخول
                <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </p>
    </div>
</div>

<script>
    function togglePassword(fieldId, iconId) {
        const passwordInput = document.getElementById(fieldId);
        const toggleIcon = document.getElementById(iconId);

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
