@extends('layouts.super')

@section('title', 'إعدادات المنصة')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <h1 class="text-3xl font-bold text-white mb-6">إعدادات المنصة</h1>

    <div class="glass-table p-6">
        <form method="POST" action="{{ route('super.settings.update') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-white mb-2">اسم المنصة</label>
                <input type="text" name="platform_name" value="Smart Attendance" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>

            <div class="mb-4">
                <label class="block text-white mb-2">البريد الإلكتروني للدعم</label>
                <input type="email" name="support_email" value="support@smart-attendance.ps" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>

            <button type="submit" class="btn-primary-glass">حفظ الإعدادات</button>
        </form>
    </div>
</div>
@endsection
