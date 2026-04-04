@extends('layouts.app')

@section('title', 'المواد الدراسية')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">المواد الدراسية</h1>
            <a href="{{ route('instructor.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-right ml-1"></i> العودة للوحة التحكم
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-primary to-accent p-4">
                        <h3 class="text-lg font-bold text-white">{{ $course->name_ar ?? $course->name }}</h3>
                        <p class="text-white/80 text-sm">كود: {{ $course->code }}</p>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">عدد الطلاب:</span>
                            <span class="font-semibold">{{ $course->students_count }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">عدد المحاضرات:</span>
                            <span class="font-semibold">{{ $course->sessions_count }}</span>
                        </div>
                        <div class="flex justify-between mb-4">
                            <span class="text-gray-600">القاعة:</span>
                            <span class="font-semibold">{{ $course->location ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <a href="{{ route('instructor.course.show', $course->id) }}"
                               class="flex-1 bg-primary text-white text-center px-3 py-2 rounded-lg text-sm hover:bg-accent transition">
                                <i class="fas fa-info-circle ml-1"></i> تفاصيل
                            </a>
                            <a href="{{ route('instructor.start-session', $course->id) }}"
                               class="flex-1 bg-green-500 text-white text-center px-3 py-2 rounded-lg text-sm hover:bg-green-600 transition">
                                <i class="fas fa-qrcode ml-1"></i> بدء محاضرة
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">لا توجد مواد مسجلة لك</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
