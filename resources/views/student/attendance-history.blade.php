@extends('layouts.app')

@section('title', 'سجل الحضور')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-history ml-2"></i>
                    سجل الحضور الكامل
                </h1>
            </div>

            <div class="overflow-x-auto p-6">
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
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $attendance->session->course->name_ar ?? $attendance->session->course->name }}</td>
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

                <div class="mt-6">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
