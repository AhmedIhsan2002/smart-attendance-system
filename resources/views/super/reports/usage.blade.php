@extends('layouts.super')

@section('title', 'تقرير استخدام المنصة')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('super.reports') }}" class="text-white/60 hover:text-white transition">
            <i class="fas fa-arrow-right text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">تقرير استخدام المنصة</h1>
            <p class="text-white/60">إحصائيات حول المستخدمين والمؤسسات والنشاط</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">إجمالي المؤسسات</p>
                    <p class="text-white text-3xl font-bold">{{ $totalOrganizations ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">إجمالي المستخدمين</p>
                    <p class="text-white text-3xl font-bold">{{ $totalUsers ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">المواد الدراسية</p>
                    <p class="text-white text-3xl font-bold">{{ $totalCourses ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">تسجيلات الحضور</p>
                    <p class="text-white text-3xl font-bold">{{ $totalAttendances ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Chart -->
    <div class="chart-container mb-6">
        <h3 class="text-white text-lg font-semibold mb-4">نمو المستخدمين (آخر 6 أشهر)</h3>
        <canvas id="usersGrowthChart" style="width:100%; height:300px;"></canvas>
    </div>

    <!-- Institutions Table -->
    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 overflow-hidden">
        <div class="p-4 border-b border-white/10">
            <h3 class="text-white font-semibold">أكثر المؤسسات نشاطاً</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10 bg-white/5">
                        <th class="text-right p-4 text-white font-semibold">#</th>
                        <th class="text-right p-4 text-white font-semibold">المؤسسة</th>
                        <th class="text-right p-4 text-white font-semibold">عدد المستخدمين</th>
                        <th class="text-right p-4 text-white font-semibold">عدد المواد</th>
                        <th class="text-right p-4 text-white font-semibold">تسجيلات الحضور</th>
                     </tr>
                </thead>
                <tbody>
                    @forelse($topOrganizations ?? [] as $org)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="p-4 text-white/80">{{ $loop->iteration }}</td>
                        <td class="p-4 text-white">{{ $org->name_ar ?? $org->name ?? '-' }}</td>
                        <td class="p-4 text-white/80">{{ $org->users_count ?? 0 }}</td>
                        <td class="p-4 text-white/80">{{ $org->courses_count ?? 0 }}</td>
                        <td class="p-4 text-white/80">{{ $org->attendances_count ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-8 text-white/50">
                            <p>لا توجد بيانات كافية لعرض التقارير</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('usersGrowthChart').getContext('2d');

        new Chart(ctx, {
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
