@extends('layouts.super')

@section('title', 'الفواتير')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">الفواتير</h1>
            <p class="text-white/60">إدارة فواتير الاشتراكات للمؤسسات</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="btn-glass">
                <i class="fas fa-print ml-2"></i> طباعة
            </button>
            <button onclick="exportInvoices()" class="btn-primary-glass">
                <i class="fas fa-file-excel ml-2"></i> تصدير Excel
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-white/60 text-sm mb-1">المؤسسة</label>
                <input type="text" name="organization" placeholder="بحث باسم المؤسسة"
                       class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>
            <div>
                <label class="block text-white/60 text-sm mb-1">الحالة</label>
                <select name="status" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
                    <option value="">الكل</option>
                    <option value="paid">مدفوعة</option>
                    <option value="pending">قيد الانتظار</option>
                    <option value="failed">فشلت</option>
                </select>
            </div>
            <div>
                <label class="block text-white/60 text-sm mb-1">من تاريخ</label>
                <input type="date" name="from_date" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>
            <div>
                <label class="block text-white/60 text-sm mb-1">إلى تاريخ</label>
                <input type="date" name="to_date" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-glass w-full">
                    <i class="fas fa-search ml-2"></i> بحث
                </button>
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10 bg-white/5">
                        <th class="text-right p-4 text-white font-semibold">#</th>
                        <th class="text-right p-4 text-white font-semibold">رقم الفاتورة</th>
                        <th class="text-right p-4 text-white font-semibold">المؤسسة</th>
                        <th class="text-right p-4 text-white font-semibold">الباقة</th>
                        <th class="text-right p-4 text-white font-semibold">المبلغ</th>
                        <th class="text-right p-4 text-white font-semibold">الحالة</th>
                        <th class="text-right p-4 text-white font-semibold">تاريخ الإصدار</th>
                        <th class="text-right p-4 text-white font-semibold">تاريخ الاستحقاق</th>
                        <th class="text-right p-4 text-white font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices ?? [] as $invoice)
                    <tr class="border-b border-white/10 hover:bg-white/5 transition">
                        <td class="p-4 text-white/80">{{ $loop->iteration }}</td>
                        <td class="p-4 text-white font-mono text-sm">#{{ $invoice->invoice_number ?? 'INV-001' }}</td>
                        <td class="p-4 text-white">{{ $invoice->organization->name_ar ?? 'مؤسسة تجريبية' }}</td>
                        <td class="p-4 text-white/80">{{ $invoice->plan->name_ar ?? 'Basic' }}</td>
                        <td class="p-4 text-white font-semibold">${{ number_format($invoice->amount ?? 49, 2) }}</td>
                        <td class="p-4">
                            @if(($invoice->status ?? 'pending') == 'paid')
                                <span class="badge-active">مدفوعة</span>
                            @elseif(($invoice->status ?? 'pending') == 'pending')
                                <span class="badge-trial">قيد الانتظار</span>
                            @else
                                <span class="badge-inactive">فشلت</span>
                            @endif
                        </td>
                        <td class="p-4 text-white/80">{{ $invoice->invoice_date ?? now()->format('Y-m-d') }}</td>
                        <td class="p-4 text-white/80">{{ $invoice->due_date ?? now()->addDays(30)->format('Y-m-d') }}</td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="#" class="text-blue-400 hover:text-blue-300" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="text-green-400 hover:text-green-300" title="تحميل PDF">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center p-12 text-white/50">
                            <i class="fas fa-file-invoice-dollar text-5xl mb-3 block"></i>
                            <p>لا توجد فواتير مسجلة بعد</p>
                            <p class="text-sm mt-1">ستظهر الفواتير هنا عند إنشاء اشتراكات للمؤسسات</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-white/10">
            {{-- {{ $invoices->links() }} --}}
            <div class="flex justify-center gap-2">
                <span class="text-white/40 text-sm">عرض 1 إلى 0 من 0 نتيجة</span>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">إجمالي الفواتير</p>
                    <p class="text-white text-2xl font-bold">${{ number_format($totalAmount ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-400"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">الفواتير المدفوعة</p>
                    <p class="text-white text-2xl font-bold">${{ number_format($paidAmount ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">متوسط قيمة الفاتورة</p>
                    <p class="text-white text-2xl font-bold">${{ number_format($averageAmount ?? 0, 2) }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-simple text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function exportInvoices() {
        alert('سيتم تصدير الفواتير إلى ملف Excel قريباً');
    }
</script>
@endsection
