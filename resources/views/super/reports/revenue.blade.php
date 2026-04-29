@extends('layouts.super')

@section('title', 'تقرير الإيرادات')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('super.reports') }}" class="text-white/60 hover:text-white transition">
            <i class="fas fa-arrow-right text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">تقرير الإيرادات</h1>
            <p class="text-white/60">تحليل الإيرادات الشهرية والسنوية للمنصة</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 p-4 mb-6">
        <form method="GET" action="{{ route('super.reports.revenue') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-white/60 text-sm mb-1">من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                       class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>
            <div>
                <label class="block text-white/60 text-sm mb-1">إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                       class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>
            <div>
                <label class="block text-white/60 text-sm mb-1">نوع التقرير</label>
                <select name="report_type" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
                    <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>شهري</option>
                    <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-glass w-full">
                    <i class="fas fa-search ml-2"></i> عرض
                </button>
            </div>
        </form>
    </div>

    <!-- Revenue Chart -->
    <div class="chart-container mb-6">
        <h3 class="text-white text-lg font-semibold mb-4">الرسم البياني للإيرادات</h3>
        <canvas id="revenueChart" style="width:100%; height:300px;"></canvas>
    </div>

    <!-- Revenue Table -->
    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 overflow-hidden">
        <div class="p-4 border-b border-white/10">
            <h3 class="text-white font-semibold">تفاصيل الإيرادات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10 bg-white/5">
                        <th class="text-right p-4 text-white font-semibold">الشهر</th>
                        <th class="text-right p-4 text-white font-semibold">عدد الفواتير</th>
                        <th class="text-right p-4 text-white font-semibold">الإيرادات</th>
                        <th class="text-right p-4 text-white font-semibold">نسبة النمو</th>
                    </table>
                </thead>
                <tbody>
                    @forelse($revenueData ?? [] as $revenue)
                    <tr class="border-b border-white/10 hover:bg-white/5">
                        <td class="p-4 text-white">{{ $revenue['month'] ?? '-' }}</td>
                        <td class="p-4 text-white/80">{{ $revenue['invoices_count'] ?? 0 }}</td>
                        <td class="p-4 text-white font-semibold">${{ number_format($revenue['amount'] ?? 0, 2) }}</td>
                        <td class="p-4">
                            @if(($revenue['growth'] ?? 0) > 0)
                                <span class="text-green-400">+{{ $revenue['growth'] }}%</span>
                            @elseif(($revenue['growth'] ?? 0) < 0)
                                <span class="text-red-400">{{ $revenue['growth'] }}%</span>
                            @else
                                <span class="text-white/40">0%</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-8 text-white/50">
                            <i class="fas fa-chart-line text-4xl mb-2 block"></i>
                            <p>لا توجد بيانات إيرادات للفترة المحددة</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t border-white/10 bg-white/5">
                    <tr>
                        <td class="p-4 text-white font-bold">الإجمالي</td>
                        <td class="p-4 text-white/80">{{ $totalInvoices ?? 0 }}</td>
                        <td class="p-4 text-white font-bold text-lg">${{ number_format($totalRevenue ?? 0, 2) }}</td>
                        <td class="p-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var canvas = document.getElementById('revenueChart');
        if (!canvas) {
            console.log('Canvas not found');
            return;
        }
        var ctx = canvas.getContext('2d');

        var monthlyData = <?php echo json_encode($monthlyData ?? [2450, 3890, 5200, 7800, 10200, 14900]); ?>;
        var monthlyLabels = <?php echo json_encode($monthlyLabels ?? ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو']); ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'الإيرادات ($)',
                    data: monthlyData,
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: { color: '#fff' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return '$' + tooltipItem.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: { color: 'rgba(255,255,255,0.1)' },
                        ticks: { color: '#fff', callback: function(value) {
                            return '$' + value;
                        } }
                    },
                    x: {
                        ticks: { color: '#fff' }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
