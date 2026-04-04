@extends('layouts.admin')

@section('title', 'لوحة تحكم الأدمن')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-2xl p-6 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">مرحباً أدمن {{ Auth::user()->name }} 👋</h1>
                    <p class="text-white/80 mt-2">لوحة تحكم المدير - إدارة النظام بالكامل</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $stats['today_attendance'] }}</div>
                    <div class="text-white/80">تسجيل حضور اليوم</div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
            <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_students'] }}</div>
                <div class="text-xs text-gray-500">الطلاب</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['total_instructors'] }}</div>
                <div class="text-xs text-gray-500">الدكاترة</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['total_courses'] }}</div>
                <div class="text-xs text-gray-500">المواد</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $stats['total_colleges'] }}</div>
                <div class="text-xs text-gray-500">الكليات</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="text-2xl font-bold text-teal-600">{{ $stats['total_departments'] }}</div>
                <div class="text-xs text-gray-500">الأقسام</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['today_attendance'] }}</div>
                <div class="text-xs text-gray-500">حضور اليوم</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['active_sessions'] }}</div>
                <div class="text-xs text-gray-500">محاضرات نشطة</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">آخر المستخدمين</h2>
                    <a href="{{ route('admin.users') }}" class="text-white/80 hover:text-white text-sm">عرض الكل</a>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentUsers as $user)
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }} - {{ $user->role == 'student' ? 'طالب' : ($user->role == 'instructor' ? 'دكتور' : 'أدمن') }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Attendances -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gray-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">آخر تسجيلات الحضور</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentAttendances as $attendance)
                        <div class="p-4">
                            <p class="font-semibold text-gray-800">{{ $attendance->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $attendance->session->course->name_ar ?? $attendance->session->course->name }}</p>
                            <p class="text-xs text-gray-400">{{ $attendance->check_in_time->format('Y-m-d h:i A') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white p-4 rounded-xl text-center hover:bg-blue-600 transition">
                <i class="fas fa-user-plus text-2xl mb-2"></i>
                <p class="text-sm">إضافة مستخدم</p>
            </a>
            <a href="{{ route('admin.colleges.create') }}" class="bg-green-500 text-white p-4 rounded-xl text-center hover:bg-green-600 transition">
                <i class="fas fa-university text-2xl mb-2"></i>
                <p class="text-sm">إضافة كلية</p>
            </a>
            <a href="{{ route('admin.departments.create') }}" class="bg-purple-500 text-white p-4 rounded-xl text-center hover:bg-purple-600 transition">
                <i class="fas fa-building text-2xl mb-2"></i>
                <p class="text-sm">إضافة قسم</p>
            </a>
            <a href="{{ route('admin.courses.create') }}" class="bg-orange-500 text-white p-4 rounded-xl text-center hover:bg-orange-600 transition">
                <i class="fas fa-book text-2xl mb-2"></i>
                <p class="text-sm">إضافة مادة</p>
            </a>
        </div>

    </div>
</div>
@endsection
