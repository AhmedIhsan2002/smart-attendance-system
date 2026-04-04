<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') | نظام الحضور الذكي</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f3f4f6;
        }
        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
        }
        .nav-link {
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            border-right: 3px solid #ef4444;
        }
    </style>
</head>
<body>
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 min-h-screen fixed">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-white text-xl font-bold text-center">نظام الحضور الذكي</h2>
                <p class="text-gray-400 text-xs text-center mt-1">لوحة تحكم الأدمن</p>
            </div>

            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5 ml-3"></i>
                    <span>لوحة التحكم</span>
                </a>

                <div class="px-4 py-2 text-gray-500 text-xs uppercase mt-4">إدارة المستخدمين</div>
                <a href="{{ route('admin.users') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5 ml-3"></i>
                    <span>المستخدمين</span>
                </a>

                <div class="px-4 py-2 text-gray-500 text-xs uppercase mt-4">إدارة الأكاديمية</div>
                <a href="{{ route('admin.colleges') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white {{ request()->routeIs('admin.colleges*') ? 'active' : '' }}">
                    <i class="fas fa-university w-5 ml-3"></i>
                    <span>الكليات</span>
                </a>
                <a href="{{ route('admin.departments') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white {{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
                    <i class="fas fa-building w-5 ml-3"></i>
                    <span>الأقسام</span>
                </a>
                <a href="{{ route('admin.courses') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white {{ request()->routeIs('admin.courses*') ? 'active' : '' }}">
                    <i class="fas fa-book w-5 ml-3"></i>
                    <span>المواد</span>
                </a>

                <div class="px-4 py-2 text-gray-500 text-xs uppercase mt-4">النظام</div>
                <a href="{{ route('admin.settings') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5 ml-3"></i>
                    <span>الإعدادات</span>
                </a>

                <hr class="border-gray-700 my-4">

                <a href="{{ route('home') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white">
                    <i class="fas fa-globe w-5 ml-3"></i>
                    <span>الموقع الرئيسي</span>
                </a>
                <a href="{{ route('logout') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 hover:text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt w-5 ml-3"></i>
                    <span>تسجيل الخروج</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 mr-64">
            <!-- Top Navbar -->
            <nav class="bg-white shadow-sm sticky top-0 z-50">
                <div class="px-6 py-3 flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-bars text-gray-600 text-xl cursor-pointer" id="sidebarToggle"></i>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-red-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                        </div>
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });
    </script>
</body>
</html>
