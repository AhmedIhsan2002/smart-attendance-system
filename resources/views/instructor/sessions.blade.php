@extends('layouts.app')

@section('title', 'سجل المحاضرات - ' . ($course->name_ar ?? $course->name))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">سجل المحاضرات</h1>
                <p class="text-gray-600">{{ $course->name_ar ?? $course->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('instructor.start-session', $course->id) }}"
                   class="btn-primary text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-plus ml-1"></i> محاضرة جديدة
                </a>
                <a href="{{ route('instructor.course.show', $course->id) }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-right ml-1"></i> العودة
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوقت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الحضور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($session->date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        @if($session->status == 'ongoing') bg-green-100 text-green-800
                                        @elseif($session->status == 'completed') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $session->status == 'ongoing' ? 'جارية' : ($session->status == 'completed' ? 'منتهية' : 'مجدولة') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $session->attendances_count }} / {{ $course->students->count() }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('instructor.session.show', $session->id) }}"
                                       class="text-primary hover:text-accent">
                                        <i class="fas fa-eye ml-1"></i> تفاصيل
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-alt text-3xl mb-2"></i>
                                    <p>لا توجد محاضرات مسجلة بعد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $sessions->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
