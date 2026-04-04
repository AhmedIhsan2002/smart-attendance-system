@extends('layouts.admin')

@section('title', 'إدارة الأقسام')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">إدارة الأقسام</h1>
            <a href="{{ route('admin.departments.create') }}" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition">
                <i class="fas fa-plus ml-1"></i> إضافة قسم
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('admin.departments') }}" class="flex gap-4">
                <select name="college_id" class="px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">جميع الكليات</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->id }}" {{ request('college_id') == $college->id ? 'selected' : '' }}>
                            {{ $college->name_ar }}
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
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الكلية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رئيس القسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($departments as $department)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $department->name_ar }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $department->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $department->code }}</td>
                                <td class="px-6 py-4">{{ $department->college->name_ar ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $department->head_name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.departments.edit', $department->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.departments.delete', $department->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
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
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">لا توجد بيانات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $departments->appends(request()->query())->links() }}</div>
        </div>

    </div>
</div>
@endsection
