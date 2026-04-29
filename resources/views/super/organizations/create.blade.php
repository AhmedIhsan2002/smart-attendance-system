@extends('layouts.super')

@section('title', 'إضافة مؤسسة جديدة')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('super.organizations') }}" class="text-white/60 hover:text-white transition">
            <i class="fas fa-arrow-right text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-white">إضافة مؤسسة جديدة</h1>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">هناك مشكلة في النموذج:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="glass-table p-6">
        <form method="POST" action="{{ route('super.organizations.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white mb-2">اسم المؤسسة (عربي) <span class="text-red-400">*</span></label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    @error('name_ar') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-white mb-2">اسم المؤسسة (إنجليزي) <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white mb-2">النطاق الفرعي <span class="text-red-400">*</span></label>
                    <div class="flex">
                        <input type="text" name="subdomain" value="{{ old('subdomain') }}"
                               class="flex-1 px-4 py-2 bg-white/10 border border-white/20 rounded-r-lg text-white focus:outline-none focus:border-primary" required>
                        <span class="px-3 py-2 bg-white/20 border border-white/20 border-r-0 rounded-l-lg text-white/60">.smart-attendance.ps</span>
                    </div>
                    @error('subdomain') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-white mb-2">البريد الإلكتروني <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-white mb-2">الباقة <span class="text-red-400">*</span></label>
                <select name="plan_id" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    <option value="">اختر الباقة</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name_ar }} - ${{ $plan->price_monthly }}/شهر
                        </option>
                    @endforeach
                </select>
                @error('plan_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-white/10 pt-6">
                <h3 class="text-white text-lg font-semibold mb-4">بيانات المدير</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white mb-2">اسم المدير <span class="text-red-400">*</span></label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    </div>

                    <div>
                        <label class="block text-white mb-2">البريد الإلكتروني للمدير <span class="text-red-400">*</span></label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                               class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-white mb-2">كلمة المرور <span class="text-red-400">*</span></label>
                    <input type="password" name="admin_password"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('super.organizations') }}" class="btn-glass">إلغاء</a>
                <button type="submit" class="btn-primary-glass">إنشاء المؤسسة</button>
            </div>
        </form>
    </div>
</div>
@endsection
