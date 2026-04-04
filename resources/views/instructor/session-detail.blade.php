@extends('layouts.app')

@section('title', 'تفاصيل المحاضرة')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">تفاصيل المحاضرة</h1>
                <p class="text-gray-600">{{ $session->course->name_ar ?? $session->course->name }}</p>
            </div>
            <a href="{{ route('instructor.course.sessions', $session->course_id) }}"
               class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-right ml-1"></i> العودة للمحاضرات
            </a>
        </div>

        <!-- Session Info -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-gray-500 text-sm">التاريخ</div>
                <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($session->date)->format('Y-m-d') }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-gray-500 text-sm">الوقت</div>
                <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-gray-500 text-sm">الحالة</div>
                <div class="text-lg font-bold">
                    <span class="px-2 py-1 rounded-full text-xs
                        @if($session->status == 'ongoing') bg-green-100 text-green-800
                        @elseif($session->status == 'completed') bg-gray-100 text-gray-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ $session->status == 'ongoing' ? 'جارية' : ($session->status == 'completed' ? 'منتهية' : 'مجدولة') }}
                    </span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-gray-500 text-sm">QR Code</div>
                <div class="text-lg font-bold">
                    @if($session->qr_expires_at > now())
                        <span class="text-green-600">صالح حتى {{ \Carbon\Carbon::parse($session->qr_expires_at)->format('h:i A') }}</span>
                    @else
                        <span class="text-red-600">منتهي الصلاحية</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
                <div class="text-3xl font-bold text-green-600">{{ $presentCount }}</div>
                <div class="text-gray-600">حاضر</div>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 text-center border border-yellow-200">
                <div class="text-3xl font-bold text-yellow-600">{{ $lateCount }}</div>
                <div class="text-gray-600">متأخر</div>
            </div>
            <div class="bg-red-50 rounded-xl p-4 text-center border border-red-200">
                <div class="text-3xl font-bold text-red-600">{{ $absentCount }}</div>
                <div class="text-gray-600">غائب</div>
            </div>
        </div>

        <!-- Attendances List -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-users ml-2"></i>
                    قائمة الحضور
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرقم الجامعي</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الطالب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وقت التسجيل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">طريقة التسجيل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-mono">{{ $attendance->user->student_id ?? '-' }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $attendance->user->name }}</td>
                                <td class="px-6 py-4">
                                    @if($attendance->status == 'present')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">حاضر</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">متأخر</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">غائب</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</td>
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
                                    <i class="fas fa-user-check text-3xl mb-2"></i>
                                    <p>لا يوجد تسجيلات حضور لهذه المحاضرة</p>
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
