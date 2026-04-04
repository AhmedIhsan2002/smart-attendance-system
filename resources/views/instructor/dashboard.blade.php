@extends('layouts.app')

@section('title', 'لوحة تحكم المحاضر')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl p-6 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">مرحباً د. {{ $instructor->name }} 👋</h1>
                    <p class="text-white/80 mt-2">لوحة تحكم المحاضر - إدارة المواد والحضور</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $todayAttendanceCount }}</div>
                    <div class="text-white/80">تسجيل حضور اليوم</div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stat-card bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">المواد الدراسية</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalCourses }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الطلاب</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">محاضرات اليوم</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $todaySessions->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Sessions -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-calendar-day ml-2"></i>
                    محاضرات اليوم
                </h2>
            </div>
            <div class="p-6">
                @if($todaySessions->count() > 0)
                    <div class="space-y-4">
                        @foreach($todaySessions as $session)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-chalkboard-user text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $session->course->name_ar ?? $session->course->name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-clock ml-1"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-location-dot ml-1"></i> {{ $session->course->location ?? 'قاعة غير محددة' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('instructor.live-attendance', $session->course_id) }}"
                                       class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600 transition">
                                        <i class="fas fa-eye ml-1"></i> حضور مباشر
                                    </a>
                                    <a href="{{ route('instructor.start-session', $session->course_id) }}"
                                       class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-accent transition">
                                        <i class="fas fa-qrcode ml-1"></i> QR Code
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-alt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">لا توجد محاضرات لليوم</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- My Courses -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-graduation-cap ml-2"></i>
                        المواد الدراسية
                    </h2>
                    <a href="{{ route('instructor.courses') }}" class="text-white/80 hover:text-white text-sm">
                        عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($courses->take(5) as $course)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $course->name_ar ?? $course->name }}</h3>
                                    <p class="text-sm text-gray-500">كود: {{ $course->code }} | عدد الطلاب: {{ $course->students_count }}</p>
                                </div>
                                <a href="{{ route('instructor.course.show', $course->id) }}"
                                   class="text-primary hover:text-accent">
                                    <i class="fas fa-arrow-left"></i> تفاصيل
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-book-open text-3xl mb-2"></i>
                            <p>لا توجد مواد مسجلة</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Sessions -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-calendar-week ml-2"></i>
                        المحاضرات القادمة
                    </h2>
                </div>
                <div class="p-4">
                    @if($upcomingSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($upcomingSessions as $session)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-medium text-gray-800">{{ $session->course->name_ar ?? $session->course->name }}</h3>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-calendar ml-1"></i> {{ \Carbon\Carbon::parse($session->date)->format('Y-m-d') }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-clock ml-1"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                    <a href="{{ route('instructor.start-session', $session->course_id) }}"
                                       class="btn-primary text-white px-3 py-1 rounded-lg text-sm">
                                        بدء
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-calendar-alt text-3xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">لا توجد محاضرات قادمة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Attendances -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-8">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-clock ml-2"></i>
                    آخر تسجيلات الحضور
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الطالب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المادة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوقت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">طريقة التسجيل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentAttendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $attendance->user->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $attendance->session->course->name_ar ?? $attendance->session->course->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</td>
                                <td class="px-6 py-4">
                                    @if($attendance->status == 'present')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">حاضر</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">متأخر</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">غائب</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance->verification_method == 'qr')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">QR Code</span>
                                    @elseif($attendance->verification_method == 'face')
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">بصمة وجه</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">يدوي</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-check text-3xl mb-2"></i>
                                    <p>لا توجد سجلات حضور بعد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
