@extends('layouts.admin')

@section('title', 'إدارة الكليات')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">إدارة الكليات</h1>
            <a href="{{ route('admin.colleges.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                <i class="fas fa-plus ml-1"></i> إضافة كلية
            </a>
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
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الأقسام</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عميد الكلية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($colleges as $college)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $college->name_ar }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $college->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $college->code }}</td>
                                <td class="px-6 py-4 text-center">{{ $college->departments_count }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $college->dean_name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.colleges.edit', $college->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.colleges.delete', $college->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الكلية؟')">
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
            <div class="p-4">{{ $colleges->links() }}</div>
        </div>

    </div>
</div>
@endsection
