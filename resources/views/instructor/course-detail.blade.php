@extends('layouts.app')

@section('title', 'تفاصيل المادة - ' . ($course->name_ar ?? $course->name))

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-gradient-to-r from-primary to-accent rounded-2xl p-6 mb-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold">{{ $course->name_ar ?? $course->name }}</h1>
                    <p class="text-white/80 mt-2">كود: {{ $course->code }} | القاعة: {{ $course->location ?? 'غير محدد' }}</p>
                    <p class="text-white/70 text-sm mt-1">{{ $course->description }}</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ $course->students->count() }}</div>
                    <div class="text-white/80 text-sm">عدد الطلاب</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 mb-8">
            <a href="{{ route('instructor.start-session', $course->id) }}"
               class="btn-primary text-white px-6 py-3 rounded-lg flex items-center">
                <i class="fas fa-qrcode ml-2"></i>
                بدء محاضرة جديدة
            </a>
            <a href="{{ route('instructor.course.sessions', $course->id) }}"
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-history ml-2"></i>
                سجل المحاضرات
            </a>
            <a href="{{ route('instructor.courses') }}"
               class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition flex items-center">
                <i class="fas fa-arrow-right ml-2"></i>
                جميع المواد
            </a>
        </div>

        <!-- Students Attendance Stats -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-chart-line ml-2"></i>
                    نسب حضور الطلاب
                </h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرقم الجامعي</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الطالب</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">حضر</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">تأخر</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">غاب</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">النسبة</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($studentsAttendance as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 font-mono text-sm">{{ $data['student']->student_id ?? '-' }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $data['student']->name }}</td>
                                    <td class="px-4 py-3 text-center text-green-600">{{ $data['present'] }}</td>
                                    <td class="px-4 py-3 text-center text-yellow-600">{{ $data['late'] }}</td>
                                    <td class="px-4 py-3 text-center text-red-600">{{ $data['absent'] }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="font-bold {{ $data['percentage'] >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $data['percentage'] }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="h-1.5 rounded-full {{ $data['percentage'] >= 75 ? 'bg-green-500' : 'bg-red-500' }}"
                                                 style="width: {{ $data['percentage'] }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($data['percentage'] >= 75)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">ملتزم</span>
                                        @elseif($data['percentage'] >= 50)
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">تحذير</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">إنذار</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Sessions -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-clock ml-2"></i>
                    آخر المحاضرات
                </h2>
                <a href="{{ route('instructor.course.sessions', $course->id) }}" class="text-white/80 hover:text-white text-sm">
                    عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($course->sessions->take(5) as $session)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($session->date)->format('Y-m-d') }}</span>
                                    <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}</span>
                                </div>
                                <div class="flex gap-2 mt-1">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($session->status == 'ongoing') bg-green-100 text-green-800
                                        @elseif($session->status == 'completed') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $session->status == 'ongoing' ? 'جارية' : ($session->status == 'completed' ? 'منتهية' : 'مجدولة') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-users ml-1"></i> {{ $session->attendances->count() }} طالب
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('instructor.session.show', $session->id) }}"
                               class="text-primary hover:text-accent text-sm">
                                تفاصيل <i class="fas fa-arrow-left mr-1"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-calendar-alt text-3xl mb-2"></i>
                        <p>لا توجد محاضرات مسجلة بعد</p>
                        <a href="{{ route('instructor.start-session', $course->id) }}" class="text-primary mt-2 inline-block">
                            بدء أول محاضرة
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
