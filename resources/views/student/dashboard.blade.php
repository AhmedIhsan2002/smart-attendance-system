@extends('layouts.app')

@section('title', 'لوحة تحكم الطالب')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-primary to-accent rounded-2xl p-6 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">مرحباً {{ $user->name }} 👋</h1>
                    <p class="text-white/80 mt-2">الرقم الجامعي: {{ $user->student_id ?? 'غير محدد' }}</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold">{{ $totalPercentage }}%</div>
                    <div class="text-white/80">نسبة الحضور الإجمالية</div>
                </div>
            </div>
        </div>
<!-- ========== أضف كود بصمة الوجه هنا ========== -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-4 mb-8 text-white">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center ml-3">
                <i class="fas fa-face-smile text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold">بصمة الوجه - تسجيل الدخول الذكي</h3>
                <p class="text-white/80 text-sm">
                    @if(Auth::user()->face_enrolled)
                        ✅ تم تسجيل بصمة وجهك
                    @else
                        ⚠️ لم يتم تسجيل بصمة وجهك بعد
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('face.enroll') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition">
            <i class="fas fa-camera ml-1"></i>
            {{ Auth::user()->face_enrolled ? 'إعادة التسجيل' : 'تسجيل بصمة الوجه' }}
        </a>
    </div>
</div>
<!-- ======================================== -->
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي المحاضرات</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalSessions }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">حضور</p>
                        <p class="text-2xl font-bold text-green-600">{{ $presentCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">تأخير</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $lateCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">غياب</p>
                        <p class="text-2xl font-bold text-red-600">{{ $absentCount }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
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
                                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                                        <i class="fas fa-book text-primary text-xl"></i>
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
                                <a href="{{ route('attendance') }}" class="btn-primary text-white px-4 py-2 rounded-lg text-sm">
                                    <i class="fas fa-fingerprint ml-1"></i> تسجيل حضور
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-alt text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">لا توجد محاضرات اليوم</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Attendance by Course -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-chart-pie ml-2"></i>
                        نسبة الحضور حسب المادة
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($attendanceStats as $stat)
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700 font-medium">{{ $stat['course_name'] }}</span>
                                    <span class="text-gray-600 text-sm">
                                        {{ $stat['present'] + $stat['late'] }}/{{ $stat['total_sessions'] }}
                                        ({{ $stat['percentage'] }}%)
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $stat['warning'] ? 'bg-yellow-500' : 'bg-green-500' }}"
                                         style="width: {{ $stat['percentage'] }}%"></div>
                                </div>
                                @if($stat['warning'])
                                    <p class="text-xs text-yellow-600 mt-1">
                                        <i class="fas fa-exclamation-triangle"></i> نسبة الحضور أقل من 75%
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if(count($attendanceStats) == 0)
                        <div class="text-center py-8">
                            <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">لا توجد مواد مسجلة</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-bell ml-2"></i>
                        آخر الإشعارات
                    </h2>
                </div>
                <div class="p-6">
                    @if($notifications->count() > 0)
                        <div class="space-y-3">
                            @foreach($notifications as $notification)
                                <div class="flex items-start space-x-3 rtl:space-x-reverse p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800 text-sm">{{ $notification->title }}</h4>
                                        <p class="text-gray-600 text-xs mt-1">{{ $notification->message }}</p>
                                        <p class="text-gray-400 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">لا توجد إشعارات جديدة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Attendance History -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-8">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-history ml-2"></i>
                    آخر تسجيلات الحضور
                </h2>
                <a href="{{ route('student.attendance-history') }}" class="text-white/80 hover:text-white text-sm">
                    عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
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
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $attendance->session->course->name_ar ?? $attendance->session->course->name }}</div>
                                </td>
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
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
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
