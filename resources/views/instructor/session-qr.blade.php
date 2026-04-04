@extends('layouts.app')

@section('title', 'QR Code المحاضرة')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 text-center">
                <h1 class="text-2xl font-bold text-white">QR Code المحاضرة</h1>
                <p class="text-white/80 mt-1">{{ $course->name_ar ?? $course->name }}</p>
            </div>

            <div class="p-8 text-center">
                <div class="bg-white p-6 rounded-2xl shadow-lg inline-block mb-6">
                    {!! $qrCode !!}
                </div>

                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800">
                            <i class="fas fa-clock ml-2"></i>
                            صلاحية QR Code: <strong>{{ \Carbon\Carbon::parse($session->qr_expires_at)->format('h:i A') }}</strong>
                        </p>
                        <p class="text-yellow-600 text-sm mt-1">ينتهي بعد 15 دقيقة من بدء المحاضرة</p>
                    </div>

                    <div class="flex gap-3 justify-center">
                        <button onclick="window.print()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                            <i class="fas fa-print ml-2"></i> طباعة
                        </button>
                        <a href="{{ route('instructor.live-attendance', $course->id) }}"
                           class="btn-primary text-white px-6 py-2 rounded-lg">
                            <i class="fas fa-users ml-2"></i> متابعة الحضور المباشر
                        </a>
                    </div>

                    <div class="border-t pt-4 mt-4">
                        <p class="text-gray-500 text-sm">
                            <i class="fas fa-info-circle ml-1"></i>
                            اطلب من الطلاب مسح هذا الرمز لتسجيل الحضور
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('instructor.course.show', $course->id) }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-right ml-1"></i> العودة لتفاصيل المادة
            </a>
        </div>

    </div>
</div>
@endsection
