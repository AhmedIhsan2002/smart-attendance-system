@extends('layouts.admin')

@section('title', 'تعديل مستخدم')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-600 to-yellow-800 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">تعديل مستخدم</h1>
                <p class="text-white/80 mt-1">تعديل بيانات المستخدم: {{ $user->name }}</p>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور (اتركها فارغة إذا لم تريد التغيير)</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الدور *</label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500" required>
                        <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>طالب</option>
                        <option value="instructor" {{ $user->role == 'instructor' ? 'selected' : '' }}>دكتور</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>أدمن</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الرقم الجامعي</label>
                    <input type="text" name="student_id" value="{{ old('student_id', $user->student_id) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">إلغاء</a>
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">تحديث</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
