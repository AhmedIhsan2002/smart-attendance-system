@extends('layouts.super')

@section('title', 'تفاصيل الفاتورة')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('super.invoices') }}" class="text-white/60 hover:text-white transition">
            <i class="fas fa-arrow-right text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-white">تفاصيل الفاتورة</h1>
    </div>

    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 p-6">
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-r from-primary to-accent rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-file-invoice-dollar text-white text-3xl"></i>
            </div>
            <h2 class="text-white text-2xl font-bold">فاتورة اشتراك</h2>
            <p class="text-white/50">#INV-2024-001</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 pb-4 border-b border-white/10">
            <div>
                <p class="text-white/50 text-sm">تاريخ الإصدار</p>
                <p class="text-white font-semibold">2024-01-15</p>
            </div>
            <div>
                <p class="text-white/50 text-sm">تاريخ الاستحقاق</p>
                <p class="text-white font-semibold">2024-02-14</p>
            </div>
            <div>
                <p class="text-white/50 text-sm">حالة الدفع</p>
                <p class="badge-active inline-block">مدفوعة</p>
            </div>
            <div>
                <p class="text-white/50 text-sm">طريقة الدفع</p>
                <p class="text-white font-semibold">بطاقة ائتمان</p>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-white font-semibold mb-3">تفاصيل الفاتورة</h3>
            <table class="w-full">
                <thead class="border-b border-white/10">
                    <tr>
                        <th class="text-right py-2 text-white/50 text-sm">الوصف</th>
                        <th class="text-right py-2 text-white/50 text-sm">الكمية</th>
                        <th class="text-right py-2 text-white/50 text-sm">السعر</th>
                        <th class="text-right py-2 text-white/50 text-sm">الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-white/5">
                        <td class="py-3 text-white">اشتراك - باقة Pro</td>
                        <td class="py-3 text-white/80">1</td>
                        <td class="py-3 text-white/80">$149.00</td>
                        <td class="py-3 text-white font-semibold">$149.00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-left py-3 text-white/50">المجموع</td>
                        <td class="py-3 text-white font-bold text-lg">$149.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex justify-between gap-4 mt-6 pt-4 border-t border-white/10">
            <a href="{{ route('super.invoices') }}" class="btn-glass">
                <i class="fas fa-arrow-right ml-1"></i> العودة
            </a>
            <div class="flex gap-2">
                <button onclick="window.print()" class="btn-glass">
                    <i class="fas fa-print ml-1"></i> طباعة
                </button>
                <button class="btn-primary-glass">
                    <i class="fas fa-download ml-1"></i> تحميل PDF
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
