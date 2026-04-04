@extends('layouts.admin')

@section('title', 'إضافة مادة')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-orange-600 to-orange-800 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">إضافة مادة جديدة</h1>
            </div>

            <form method="POST" action="{{ route('admin.courses.store') }}" class="p-6">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">عدد الساعات المعتمدة *</label>
                        <input type="number" name="credit_hours" value="{{ old('credit_hours', 3) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">القسم *</label>
                        <select name="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">اختر القسم</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->college->name_ar ?? '' }} - {{ $department->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الدكتور *</label>
                        <select name="instructor_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            <option value="">اختر الدكتور</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">وقت البداية *</label>
                        <input type="time" name="start_time" value="{{ old('start_time', '09:00') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">وقت النهاية *</label>
                        <input type="time" name="end_time" value="{{ old('end_time', '11:00') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">الفصل الدراسي *</label>
                        <input type="text" name="semester" value="{{ old('semester', 'Spring 2024') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">القاعة</label>
                        <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.courses') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg">إلغاء</a>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg">حفظ</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
