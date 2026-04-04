@extends('layouts.admin')

@section('title', 'إضافة كلية')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-800 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">إضافة كلية جديدة</h1>
            </div>

            <form method="POST" action="{{ route('admin.colleges.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الاسم (عربي) *</label>
                        <input type="text" name="name_ar" value="{{ old('name_ar') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الاسم (إنجليزي) *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الكود *</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">عميد الكلية</label>
                        <input type="text" name="dean_name" value="{{ old('dean_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">المبنى</label>
                    <input type="text" name="building" value="{{ old('building') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.colleges') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">إلغاء</a>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">حفظ</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
