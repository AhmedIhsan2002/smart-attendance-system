@extends('layouts.super')

@section('title', 'إدارة الخطط')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">إدارة الخطط والباقات</h1>
            <p class="text-white/60">إدارة خطط الاشتراك المتاحة للمؤسسات</p>
        </div>
        <a href="{{ route('super.plans.create') }}" class="btn-primary-glass">
            <i class="fas fa-plus ml-2"></i> خطة جديدة
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach($plans as $plan)
        <div class="stat-card {{ $plan->is_active ? '' : 'opacity-60' }}">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-r from-primary to-accent rounded-2xl flex items-center justify-center mx-auto mb-3">
                    @if($plan->slug == 'basic')
                        <i class="fas fa-star text-white text-2xl"></i>
                    @elseif($plan->slug == 'pro')
                        <i class="fas fa-gem text-white text-2xl"></i>
                    @elseif($plan->slug == 'enterprise')
                        <i class="fas fa-building text-white text-2xl"></i>
                    @else
                        <i class="fas fa-university text-white text-2xl"></i>
                    @endif
                </div>
                <h3 class="text-white text-xl font-bold">{{ $plan->name_ar }}</h3>
                <p class="text-white/40 text-sm">{{ $plan->name }}</p>
            </div>

            <div class="text-center mb-4">
                <span class="text-white text-3xl font-bold">${{ number_format($plan->price_monthly, 2) }}</span>
                <span class="text-white/40">/شهر</span>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-white/60">الطلاب</span>
                    <span class="text-white">{{ number_format($plan->max_students) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">الدكاترة</span>
                    <span class="text-white">{{ number_format($plan->max_instructors) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">المواد</span>
                    <span class="text-white">{{ number_format($plan->max_courses) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">بصمة الوجه</span>
                    <span class="text-white">{{ $plan->has_face_recognition ? '✅' : '❌' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">API</span>
                    <span class="text-white">{{ $plan->has_api_access ? '✅' : '❌' }}</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-white/10">
                <div class="flex gap-2">
                    <a href="{{ route('super.plans.edit', $plan->id) }}" class="flex-1 btn-glass text-center">
                        <i class="fas fa-edit ml-1"></i> تعديل
                    </a>
                    <form action="{{ route('super.plans.delete', $plan->id) }}" method="POST" class="flex-1" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخطة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-glass text-red-400 hover:text-red-300">
                            <i class="fas fa-trash ml-1"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
