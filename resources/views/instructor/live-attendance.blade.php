@extends('layouts.app')

@section('title', 'حضور مباشر - ' . ($course->name_ar ?? $course->name))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">حضور مباشر</h1>
                <p class="text-gray-600">{{ $course->name_ar ?? $course->name }} - {{ \Carbon\Carbon::parse($todaySession->date)->format('Y-m-d') }}</p>
            </div>
            <a href="{{ route('instructor.course.show', $course->id) }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-right ml-1"></i> العودة
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-3xl font-bold text-green-600">{{ $attendances->where('status', 'present')->count() }}</div>
                <div class="text-gray-600">حاضر</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-3xl font-bold text-yellow-600">{{ $attendances->where('status', 'late')->count() }}</div>
                <div class="text-gray-600">متأخر</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 text-center">
                <div class="text-3xl font-bold text-red-600">{{ $allStudents->count() - $attendances->count() }}</div>
                <div class="text-gray-600">لم يسجل بعد</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white">قائمة الطلاب</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        32
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرقم الجامعي</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الطالب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وقت التسجيل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">طريقة التسجيل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($allStudents as $student)
                            @php
                                $attendance = $attendances->where('user_id', $student->id)->first();
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $student->student_id ?? '-' }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $student->name }}</td>
                                <td class="px-6 py-4">
                                    @if($attendance)
                                        @if($attendance->status == 'present')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">حاضر</span>
                                        @elseif($attendance->status == 'late')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">متأخر</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">غائب</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $attendance ? \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance)
                                        @if($attendance->verification_method == 'qr')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">QR Code</span>
                                        @elseif($attendance->verification_method == 'face')
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">بصمة وجه</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">يدوي</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 text-center">
            <form action="{{ route('instructor.session.end', $todaySession->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition"
                        onclick="return confirm('هل أنت متأكد من إنهاء المحاضرة؟')">
                    <i class="fas fa-stop-circle ml-2"></i> إنهاء المحاضرة
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
