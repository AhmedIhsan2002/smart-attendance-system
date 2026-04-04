@extends('layouts.app')

@section('title', 'التقارير والإحصائيات')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-accent px-6 py-6">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-chart-line ml-2"></i>
                    التقارير والإحصائيات
                </h1>
                <p class="text-white/80 mt-1">تحليل شامل للحضور والغياب</p>
            </div>

            <!-- Filter Form -->
            <div class="p-6 border-b border-gray-200">
                <form method="GET" action="{{ route('reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المادة</label>
                        <select name="course_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            <option value="">جميع المواد</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ $selectedCourse == $course->id ? 'selected' : '' }}>
                                    {{ $course->name_ar ?? $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg">
                            <i class="fas fa-search ml-1"></i> عرض
                        </button>
                        <a href="{{ route('reports') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                            <i class="fas fa-undo-alt ml-1"></i>
                        </a>
                    </div>
                </form>
            </div>

           <!-- Export Buttons -->
                <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-end gap-2">
                    <a href="{{ route('reports.export-excel') }}?{{ http_build_query(request()->query()) }}"
                    class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600 transition inline-flex items-center">
                        <i class="fas fa-file-excel ml-1"></i> تصدير Excel
                    </a>
                </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-6 bg-gray-50">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
                    <div class="text-xs text-gray-500">إجمالي التسجيلات</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['present'] }}</div>
                    <div class="text-xs text-gray-500">حاضر</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['late'] }}</div>
                    <div class="text-xs text-gray-500">متأخر</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['absent'] }}</div>
                    <div class="text-xs text-gray-500">غائب</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary">{{ $stats['present_percentage'] }}%</div>
                    <div class="text-xs text-gray-500">نسبة الحضور</div>
                </div>
            </div>

            <!-- Charts -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 mb-4">الرسم البياني للحضور</h2>
                <canvas id="attendanceChart" height="100"></canvas>
            </div>

            <!-- Attendance Table -->
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">سجل الحضور</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الطالب</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المادة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوقت</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">طريقة التسجيل</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($attendanceData as $attendance)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">{{ $attendance->check_in_time->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $attendance->user->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $attendance->session->course->name_ar ?? $attendance->session->course->name }}</td>
                                    <td class="px-4 py-3">
                                        @if($attendance->status == 'present')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">حاضر</span>
                                        @elseif($attendance->status == 'late')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">متأخر</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">غائب</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $attendance->check_in_time->format('h:i A') }}</td>
                                    <td class="px-4 py-3">
                                        @if($attendance->verification_method == 'qr')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">QR Code</span>
                                        @elseif($attendance->verification_method == 'face')
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">بصمة وجه</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">يدوي</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-chart-line text-3xl mb-2"></i>
                                        <p>لا توجد بيانات للفترة المحددة</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $attendanceData->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // الرسم البياني
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [
                {
                    label: 'حاضر',
                    data: chartData.present,
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'متأخر',
                    data: chartData.late,
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'غائب',
                    data: chartData.absent,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    rtl: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

   

    // تصدير Excel
    function exportExcel() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = '{{ route("reports.export-excel") }}?' + params.toString();
    }
</script>
@endpush
@endsection
