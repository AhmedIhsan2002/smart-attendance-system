@extends('layouts.admin')

@section('title', 'إضافة قسم')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">إضافة قسم جديد</h1>
            </div>

            <form method="POST" action="{{ route('admin.departments.store') }}" class="p-6">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الكلية *</label>
                    <select name="college_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        <option value="">اختر الكلية</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}" {{ old('college_id') == $college->id ? 'selected' : '' }}>
                                {{ $college->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
                        <label class="block text-sm font-medium text-gray-700 mb-2">رئيس القسم</label>
                        <input type="text" name="head_name" value="{{ old('head_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.departments') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">إلغاء</a>
                    <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded-lg">حفظ</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
