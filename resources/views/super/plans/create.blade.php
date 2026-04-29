@extends('layouts.super')

@section('title', 'إضافة خطة جديدة')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('super.plans') }}" class="text-white/60 hover:text-white transition">
            <i class="fas fa-arrow-right text-xl"></i>
        </a>
        <h1 class="text-3xl font-bold text-white">إضافة خطة جديدة</h1>
    </div>

    <div class="bg-white/5 backdrop-blur rounded-2xl border border-white/10 p-6">
        <form method="POST" action="{{ route('super.plans.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- الاسم (عربي) -->
                <div>
                    <label class="block text-white mb-2">الاسم (عربي) <span class="text-red-400">*</span></label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    @error('name_ar') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- الاسم (إنجليزي) -->
                <div>
                    <label class="block text-white mb-2">الاسم (إنجليزي) <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- Slug -->
                <div>
                    <label class="block text-white mb-2">Slug <span class="text-red-400">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary"
                           placeholder="basic, pro, enterprise" required>
                    @error('slug') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- ترتيب العرض -->
                <div>
                    <label class="block text-white mb-2">ترتيب العرض</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <!-- السعر شهرياً -->
                <div>
                    <label class="block text-white mb-2">السعر شهرياً ($) <span class="text-red-400">*</span></label>
                    <input type="number" name="price_monthly" value="{{ old('price_monthly') }}" step="0.01"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                    @error('price_monthly') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- السعر سنوياً -->
                <div>
                    <label class="block text-white mb-2">السعر سنوياً ($)</label>
                    <input type="number" name="price_yearly" value="{{ old('price_yearly') }}" step="0.01"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- الحد الأقصى للطلاب -->
                <div>
                    <label class="block text-white mb-2">الحد الأقصى للطلاب <span class="text-red-400">*</span></label>
                    <input type="number" name="max_students" value="{{ old('max_students', 100) }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                </div>

                <!-- الحد الأقصى للدكاترة -->
                <div>
                    <label class="block text-white mb-2">الحد الأقصى للدكاترة <span class="text-red-400">*</span></label>
                    <input type="number" name="max_instructors" value="{{ old('max_instructors', 10) }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                </div>

                <!-- الحد الأقصى للمواد -->
                <div>
                    <label class="block text-white mb-2">الحد الأقصى للمواد <span class="text-red-400">*</span></label>
                    <input type="number" name="max_courses" value="{{ old('max_courses', 20) }}"
                           class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:border-primary" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- الميزات الإضافية -->
                <div>
                    <label class="block text-white mb-2">بصمة الوجه</label>
                    <select name="has_face_recognition" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
                        <option value="0" {{ old('has_face_recognition') == '0' ? 'selected' : '' }}>لا</option>
                        <option value="1" {{ old('has_face_recognition') == '1' ? 'selected' : '' }}>نعم</option>
                    </select>
                </div>

                <div>
                    <label class="block text-white mb-2">API Access</label>
                    <select name="has_api_access" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
                        <option value="0" {{ old('has_api_access') == '0' ? 'selected' : '' }}>لا</option>
                        <option value="1" {{ old('has_api_access') == '1' ? 'selected' : '' }}>نعم</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-8 pt-4 border-t border-white/10">
                <a href="{{ route('super.plans') }}" class="btn-glass">إلغاء</a>
                <button type="submit" class="btn-primary-glass">حفظ الخطة</button>
            </div>
        </form>
    </div>
</div>
@endsection
