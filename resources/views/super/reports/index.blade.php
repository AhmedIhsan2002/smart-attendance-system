@extends('layouts.super')

@section('title', 'التقارير المتقدمة')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">التقارير المتقدمة</h1>
        <p class="text-white/60">تحليلات وإحصائيات متقدمة لمنصة Smart Attendance</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('super.reports.revenue') }}" class="stat-card hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-white text-lg font-semibold">تقرير الإيرادات</h3>
                    <p class="text-white/50 text-sm mt-1">تحليل الإيرادات الشهرية والسنوية</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-white/40 text-sm">عرض التفاصيل <i class="fas fa-arrow-left mr-1"></i></span>
            </div>
        </a>

        <a href="{{ route('super.reports.usage') }}" class="stat-card hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-white text-lg font-semibold">تقرير استخدام المنصة</h3>
                    <p class="text-white/50 text-sm mt-1">إحصائيات المستخدمين والمؤسسات</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-white/40 text-sm">عرض التفاصيل <i class="fas fa-arrow-left mr-1"></i></span>
            </div>
        </a>

        <a href="#" class="stat-card hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-white text-lg font-semibold">تقرير المؤسسات</h3>
                    <p class="text-white/50 text-sm mt-1">تحليل أداء المؤسسات المسجلة</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-white/40 text-sm">قريباً <i class="fas fa-arrow-left mr-1"></i></span>
            </div>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
            <p class="text-white/50 text-sm">إجمالي المؤسسات</p>
            <p class="text-white text-2xl font-bold">{{ $stats['total_organizations'] ?? 0 }}</p>
        </div>
        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
            <p class="text-white/50 text-sm">إجمالي المستخدمين</p>
            <p class="text-white text-2xl font-bold">{{ $stats['total_users'] ?? 0 }}</p>
        </div>
        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
            <p class="text-white/50 text-sm">الإيرادات الشهرية</p>
            <p class="text-white text-2xl font-bold">${{ number_format($stats['monthly_recurring_revenue'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white/5 rounded-xl p-4 border border-white/10">
            <p class="text-white/50 text-sm">الإيرادات الإجمالية</p>
            <p class="text-white text-2xl font-bold">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
        </div>
    </div>
</div>
@endsection
