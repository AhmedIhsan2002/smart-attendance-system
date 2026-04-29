@extends('layouts.super')

@section('title', 'لوحة التحكم')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Welcome Section -->
    <div class="mb-8 animate__animated animate__fadeInUp">
        <h1 class="text-3xl font-bold text-white mb-2">مرحباً، {{ Auth::user()->name }} 👋</h1>
        <p class="text-white/60">هذه نظرة عامة على أداء منصة Smart Attendance</p>
    </div>

   <!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
    <!-- بطاقة المؤسسات -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/60 text-sm">إجمالي المؤسسات</p>
                <p class="text-white text-3xl font-bold mt-2">{{ number_format($stats['total_organizations']) }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-building text-white text-xl"></i>
            </div>
        </div>
        <div class="mt-3">
            <div class="flex justify-between text-xs text-white/40 mb-1">
                <span>نشطة: {{ $stats['active_organizations'] ?? 0 }}</span>
                <span>تجريبية: {{ $stats['trial_organizations'] ?? 0 }}</span>
                <span>موقوفة: {{ $stats['suspended_organizations'] ?? 0 }}</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-1.5">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-1.5 rounded-full"
                     style="width: {{ ($stats['total_organizations'] > 0 ? ($stats['active_organizations'] / $stats['total_organizations']) * 100 : 0) }}%">
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقة المستخدمين -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/60 text-sm">المستخدمين</p>
                <p class="text-white text-3xl font-bold mt-2">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
        </div>
        <div class="mt-2 text-xs text-white/40 flex justify-between">
            <span>طلاب: {{ $stats['total_students'] ?? 0 }}</span>
            <span>دكاترة: {{ $stats['total_instructors'] ?? 0 }}</span>
            <span>أدمن: {{ $stats['total_admins'] ?? 0 }}</span>
        </div>
    </div>

    <!-- بطاقة الإيرادات الشهرية -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/60 text-sm">الإيرادات الشهرية</p>
                <p class="text-white text-3xl font-bold mt-2">${{ number_format($stats['monthly_recurring_revenue'], 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-white text-xl"></i>
            </div>
        </div>
        <div class="mt-2">
            <p class="text-green-400 text-xs"><i class="fas fa-arrow-up"></i> +12% عن الشهر الماضي</p>
        </div>
    </div>

    <!-- بطاقة الإيرادات الإجمالية -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-white/60 text-sm">الإيرادات الإجمالية</p>
                <p class="text-white text-3xl font-bold mt-2">${{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="chart-container">
        <h3 class="text-white text-lg font-semibold mb-4">المؤسسات المسجلة</h3>
        <canvas id="organizationsChart" height="100"></canvas>
    </div>
    <div class="chart-container">
        <h3 class="text-white text-lg font-semibold mb-4">الإيرادات الشهرية</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>
    <div class="chart-container">
        <h3 class="text-white text-lg font-semibold mb-4">المستخدمين الجدد</h3>
        <canvas id="usersChart" height="100"></canvas>
    </div>
</div>

<!-- Recent Organizations Table -->
<div class="glass-table mb-8">
    <div class="p-6 border-b border-white/10">
        <div class="flex justify-between items-center">
            <h3 class="text-white text-lg font-semibold">أحدث المؤسسات</h3>
            <a href="{{ route('super.organizations') }}" class="text-white/60 hover:text-white text-sm transition">
                عرض الكل <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-right p-4">المؤسسة</th>
                    <th class="text-right p-4">البريد الإلكتروني</th>
                    <th class="text-right p-4">الباقة</th>
                    <th class="text-right p-4">الحالة</th>
                    <th class="text-right p-4">تاريخ التسجيل</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrganizations as $org)
                <tr class="border-t border-white/10 hover:bg-white/5">
                    <td class="p-4 font-semibold">{{ $org->name_ar }} {{ $org->name }}</td>
                    <td class="p-4">{{ $org->email }}</td>
                    <td class="p-4">{{ $org->plan->name_ar ?? '-' }}</td>
                    <td class="p-4">
                        @if($org->subscription_status == 'active')
                            <span class="badge-active">نشط</span>
                        @elseif($org->subscription_status == 'trial')
                            <span class="badge-trial">نسخة تجريبية</span>
                        @else
                            <span class="badge-inactive">غير نشط</span>
                        @endif
                    </td>
                    <td class="p-4">{{ $org->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-8 text-white/50">لا توجد مؤسسات مسجلة بعد</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('super.organizations.create') }}" class="btn-primary-glass text-center">
            <i class="fas fa-plus ml-2"></i> مؤسسة جديدة
        </a>
        <a href="{{ route('super.plans.create') }}" class="btn-glass text-center">
            <i class="fas fa-tag ml-2"></i> باقة جديدة
        </a>
        <a href="#" class="btn-glass text-center">
            <i class="fas fa-file-export ml-2"></i> تصدير التقرير
        </a>
        <a href="#" class="btn-glass text-center">
            <i class="fas fa-envelope ml-2"></i> إرسال تنبيه للجميع
        </a>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ إخفاء جميع العناصر أولاً ثم ظهورها بالترتيب
        gsap.set('.stat-card', { opacity: 0, y: 30 });
        gsap.set('.chart-container', { opacity: 0, scale: 0.95 });
        gsap.set('.glass-table', { opacity: 0, y: 20 });

        // ✅ ظهور البطاقات واحدة تلو الأخرى
        gsap.to('.stat-card', {
            duration: 0.6,
            opacity: 1,
            y: 0,
            stagger: 0.1,
            ease: 'back.out(1.2)',
            delay: 0.3
        });

        // ✅ ظهور الرسوم البيانية
        gsap.to('.chart-container', {
            duration: 0.6,
            opacity: 1,
            scale: 1,
            stagger: 0.15,
            delay: 0.8,
            ease: 'power2.out'
        });

        // ✅ ظهور الجدول
        gsap.to('.glass-table', {
            duration: 0.5,
            opacity: 1,
            y: 0,
            delay: 1.1
        });

        // ✅ رسم البياني للمؤسسات (بيانات حقيقية)
        const chartData = @json($chartData ?? null);

        if (chartData && chartData.months) {
            new Chart(document.getElementById('organizationsChart'), {
                type: 'bar',
                data: {
                    labels: chartData.months,
                    datasets: [{
                        label: 'عدد المؤسسات الجديدة',
                        data: chartData.counts,
                        backgroundColor: 'rgba(102, 126, 234, 0.8)',
                        borderRadius: 8,
                        barPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { labels: { color: '#fff' } } },
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#fff', stepSize: 1, precision: 0 } },
                        x: { grid: { display: false }, ticks: { color: '#fff' } }
                    }
                }
            });
        } else {
            // بيانات افتراضية
            new Chart(document.getElementById('organizationsChart'), {
                type: 'line',
                data: {
                    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                    datasets: [{
                        label: 'المؤسسات الجديدة',
                        data: [5, 8, 12, 18, 25, 32],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { labels: { color: '#fff' } } },
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#fff' } },
                        x: { ticks: { color: '#fff' } }
                    }
                }
            });
        }

        // ✅ رسم البياني للإيرادات
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'الإيرادات ($)',
                    data: [2450, 3890, 5200, 7800, 10200, 14900],
                    backgroundColor: 'rgba(118, 75, 162, 0.8)',
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#fff' } } },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#fff', callback: (v) => '$' + v } },
                    x: { ticks: { color: '#fff' } }
                }
            }
        });
        // ✅ رسم بياني للمستخدمين
new Chart(document.getElementById('usersChart'), {
    type: 'line',
    data: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
        datasets: [{
            label: 'المستخدمين الجدد',
            data: [12, 19, 25, 32, 45, 58],
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#fff' } } },
        scales: {
            y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#fff' } },
            x: { ticks: { color: '#fff' } }
        }
    }
});
    });
</script>
@endpush
@endsection
