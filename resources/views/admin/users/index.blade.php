@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">إدارة المستخدمين</h1>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-plus ml-1"></i> إضافة مستخدم
            </a>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('admin.users') }}" class="flex gap-4">
                <div>
                    <select name="role" class="px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="all">جميع الأدوار</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>طالب</option>
                        <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>دكتور</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>أدمن</option>
                    </select>
                </div>
                <div class="flex-1">
                    <input type="text" name="search" placeholder="بحث بالاسم أو البريد أو الرقم الجامعي"
                           value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-search ml-1"></i> بحث
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد الإلكتروني</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرقم الجامعي</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الدور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ التسجيل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->student_id ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role == 'student')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">طالب</span>
                                    @elseif($user->role == 'instructor')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">دكتور</span>
                                    @else
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">أدمن</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">نشط</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">موقوف</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-800">
                                                <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                            </button>
                                        </form>
                                        @if(!($user->role == 'admin' && \App\Models\User::where('role', 'admin')->count() == 1))
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-3xl mb-2"></i>
                                    <p>لا توجد بيانات</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
