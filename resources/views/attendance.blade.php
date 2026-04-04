@extends('layouts.app')

@section('title', 'تسجيل الحضور')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-accent px-6 py-8 text-center">
                <h1 class="text-2xl font-bold text-white">تسجيل الحضور</h1>
                <p class="text-white/80 mt-2">اختر طريقة تسجيل الحضور</p>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg flex items-center">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 p-4 bg-yellow-100 text-yellow-700 rounded-lg flex items-center">
                        <i class="fas fa-exclamation-triangle ml-2"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                <!-- QR Code Method -->
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 mb-6 text-center hover:border-primary transition cursor-pointer"
                     onclick="document.getElementById('qrInput').focus()">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-qrcode text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">مسح QR Code</h3>
                    <p class="text-gray-600 mb-4">امسح الرمز الموجود في القاعة الدراسية</p>

                    <form action="{{ route('attendance.qr.input') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="flex gap-2">
                            <input type="text" name="qr_code" id="qrInput"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                   placeholder="أدخل رمز QR أو امسحه">
                            <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                                <i class="fas fa-check ml-1"></i> تأكيد
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Face Recognition Method (Coming Soon) -->
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center opacity-50">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-face-smile text-purple-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">بصمة الوجه</h3>
                    <p class="text-gray-600">قريباً - سيتم إضافة ميزة التعرف على الوجه</p>
                    <span class="inline-block mt-2 px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-sm">قريباً</span>
                </div>
            </div>
        </div>

        <!-- Today's Sessions -->
        @if(isset($todaySessions) && $todaySessions->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mt-6">
                <div class="bg-gray-800 px-6 py-4">
                    <h2 class="text-xl font-bold text-white">محاضرات اليوم</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($todaySessions as $session)
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $session->course->name_ar ?? $session->course->name }}</h3>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-clock ml-1"></i> {{ \Carbon\Carbon::parse($session->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-location-dot ml-1"></i> {{ $session->course->location ?? 'قاعة غير محددة' }}
                                </p>
                            </div>
                            @if($session->qr_code)
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">رمز QR</div>
                                    <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ substr($session->qr_code, 0, 15) }}...</code>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
