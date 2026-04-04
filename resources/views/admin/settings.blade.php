@extends('layouts.admin')

@section('title', 'إعدادات النظام')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">إعدادات النظام</h1>
                <p class="text-white/80 mt-1">تخصيص إعدادات النظام العامة</p>
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}" class="p-6">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم النظام</label>
                    <input type="text" name="system_name" value="نظام الحضور الذكي" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الوقت المسموح للتأخير (دقائق)</label>
                    <input type="number" name="late_threshold" value="15" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">بعد هذا الوقت يعتبر الطالب متأخراً</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">صلاحية QR Code (دقائق)</label>
                    <input type="number" name="qr_expiry" value="15" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">نسبة الغياب المسموحة (%)</label>
                    <input type="number" name="max_absence_percentage" value="25" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-gray-500 text-xs mt-1">إذا تجاوز الطالب هذه النسبة يتم إرسال إنذار</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-accent transition">حفظ الإعدادات</button>
                </div>
            </form>
        </div>

        <!-- System Info -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mt-6">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">معلومات النظام</h2>
            </div>
            <div class="p-6">
                <div class="space-y-2">
                    <p><strong>إصدار Laravel:</strong> {{ app()->version() }}</p>
                    <p><strong>إصدار PHP:</strong> {{ phpversion() }}</p>
                    <p><strong>قاعدة البيانات:</strong> MySQL</p>
                    <p><strong>عدد الجداول:</strong> 9</p>
                    <p><strong>عدد المستخدمين:</strong> {{ \App\Models\User::count() }}</p>
                    <p><strong>عدد المواد:</strong> {{ \App\Models\Course::count() }}</p>
                    <p><strong>عدد تسجيلات الحضور:</strong> {{ \App\Models\Attendance::count() }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
