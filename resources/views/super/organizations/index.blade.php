@extends('layouts.super')

@section('title', 'إدارة المؤسسات')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">إدارة المؤسسات</h1>
            <p class="text-white/60">إدارة جميع المؤسسات المسجلة على المنصة</p>
        </div>
        <a href="{{ route('super.organizations.create') }}" class="btn-primary-glass">
            <i class="fas fa-plus ml-2"></i> مؤسسة جديدة
        </a>
    </div>

    <!-- عرض عدد المؤسسات -->
    <div class="mb-4 text-white/80">
        عدد المؤسسات: <span class="font-bold text-white">{{ $organizations->total() }}</span>
    </div>

    <!-- جدول المؤسسات -->
    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-right py-4 px-4 text-white font-semibold">#</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">المؤسسة</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">النطاق الفرعي</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">البريد الإلكتروني</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">الباقة</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">الحالة</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">المدير</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">تاريخ التسجيل</th>
                        <th class="text-right py-4 px-4 text-white font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $index => $org)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition">
                        <td class="py-4 px-4 text-white/80">{{ $index + 1 }}</td>
                        <td class="py-4 px-4">
                            <span class="font-semibold text-white">{{ $org->name_ar }}</span>
                            <span class="text-white/40 text-sm">({{ $org->name }})</span>
                        </td>
                        <td class="py-4 px-4 text-white/80 text-sm">{{ $org->subdomain }}.smart-attendance.ps</td>
                        <td class="py-4 px-4 text-white/80">{{ $org->email }}</td>
                        <td class="py-4 px-4">
                            <span class="text-white/80">{{ $org->plan->name_ar ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-4">
                            @if($org->subscription_status == 'active')
                                <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs">نشط</span>
                            @elseif($org->subscription_status == 'trial')
                                <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-xs">نسخة تجريبية</span>
                            @else
                                <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-xs">غير نشط</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-white/80">{{ $org->owner->name ?? '-' }}</td>
                        <td class="py-4 px-4 text-white/80">{{ $org->created_at->format('Y-m-d') }}</td>
                        <td class="py-4 px-4">
                            <div class="flex gap-2">
                                <a href="{{ route('super.organizations.edit', $org->id) }}" class="text-blue-400 hover:text-blue-300 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('super.organizations.delete', $org->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المؤسسة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-12 text-white/50">
                            <i class="fas fa-building text-4xl mb-3 block"></i>
                            <p>لا توجد مؤسسات مسجلة بعد</p>
                            <a href="{{ route('super.organizations.create') }}" class="btn-primary-glass mt-4 inline-block">
                                أضف مؤسسة جديدة
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($organizations->hasPages())
        <div class="border-t border-white/10 p-4">
            {{ $organizations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
