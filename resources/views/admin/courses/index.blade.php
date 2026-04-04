@extends('layouts.admin')

@section('title', 'إدارة المواد')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">إدارة المواد</h1>
            <a href="{{ route('admin.courses.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                <i class="fas fa-plus ml-1"></i> إضافة مادة
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('admin.courses') }}" class="flex gap-4">
                <select name="department_id" class="px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">جميع الأقسام</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->college->name_ar ?? '' }} - {{ $department->name_ar }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">فلترة</button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم (عربي)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم (إنجليزي)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الكود</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">القسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الدكتور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الساعات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوقت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($courses as $course)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $course->name_ar }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $course->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $course->code }}</td>
                                <td class="px-6 py-4">{{ $course->department->name_ar ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $course->instructor->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">{{ $course->credit_hours }}</td>
                                <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($course->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($course->end_time)->format('h:i A') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.courses.delete', $course->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $courses->appends(request()->query())->links() }}</div>
        </div>

    </div>
</div>
@endsection
