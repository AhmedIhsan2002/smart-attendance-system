@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<style>
    /* Custom Animations */
    .hero-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.3;
        pointer-events: none;
    }

    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    .feature-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
    }

    .feature-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .glow-text {
        text-shadow: 0 0 20px rgba(255,255,255,0.5);
    }

    .btn-glow {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-glow::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-glow:hover::before {
        width: 300px;
        height: 300px;
    }

    .typewriter {
        overflow: hidden;
        border-left: 2px solid white;
        white-space: nowrap;
        margin: 0 auto;
        animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
    }

    @keyframes typing {
        from { width: 0; }
        to { width: 100%; }
    }

    @keyframes blink-caret {
        from, to { border-color: transparent; }
        50% { border-color: white; }
    }

    .parallax-bg {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .pulse-ring {
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
        }
        70% {
            box-shadow: 0 0 0 20px rgba(102, 126, 234, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
        }
    }
</style>

<!-- Hero Section with Parallax -->
<div class="hero-bg min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-3xl animate-pulse delay-500"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-10">
        <div class="text-center">
            <!-- Floating Badge -->
            <div class="inline-block mb-6 floating">
                <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-robot ml-2"></i> AI-Powered Attendance System
                </span>
            </div>

            <!-- Main Title with Animation -->
            <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 animate__animated animate__fadeInUp">
                نظام الحضور والانصراف
                <span class="gradient-text block mt-2">الذكي</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-3xl mx-auto animate__animated animate__fadeInUp animate__delay-1s">
                باستخدام تقنيات الذكاء الاصطناعي والتعرف على الوجه، نقدم حلًا متكاملًا لإدارة الحضور في الجامعات الفلسطينية
            </p>

            <!-- Buttons -->
            <div class="flex flex-wrap justify-center gap-4 animate__animated animate__fadeInUp animate__delay-2s">
                @guest
                    <a href="{{ route('register') }}" class="btn-primary text-white px-8 py-3 rounded-lg text-lg font-semibold btn-glow inline-flex items-center group">
                        ابدأ الآن
                        <i class="fas fa-arrow-left mr-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#features" class="bg-white/20 backdrop-blur text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-white/30 transition-all hover:scale-105 inline-flex items-center">
                        <i class="fas fa-play-circle ml-2"></i>
                        تعرف على المزيد
                    </a>
                @endguest
                @auth
                    <a href="{{ auth()->user()->role == 'student' ? route('student.dashboard') : (auth()->user()->role == 'instructor' ? route('instructor.dashboard') : route('admin.dashboard')) }}"
                       class="btn-primary text-white px-8 py-3 rounded-lg text-lg font-semibold btn-glow inline-flex items-center">
                        <i class="fas fa-tachometer-alt ml-2"></i>
                        الذهاب إلى لوحة التحكم
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <a href="#features" class="text-white/70 hover:text-white transition">
            <i class="fas fa-chevron-down text-2xl"></i>
        </a>
    </div>
</div>

<!-- Features Section with Cards Animation -->
<div id="features" class="bg-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">مميزات النظام</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-primary to-accent mx-auto rounded-full"></div>
            <p class="text-xl text-gray-600 mt-4">تقنيات حديثة لحضور أكثر دقة وأمان</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="feature-card bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-primary to-accent rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300 pulse-ring">
                    <i class="fas fa-face-smile text-white text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">بصمة الوجه</h3>
                <p class="text-gray-600">تقنية متطورة للتعرف على الوجه بدقة عالية، تمنع انتحال الشخصية وتضمن حضور الطالب بنفسه</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-primary text-sm">اقرأ المزيد <i class="fas fa-arrow-left mr-1"></i></span>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="feature-card bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-qrcode text-white text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">QR Code ديناميكي</h3>
                <p class="text-gray-600">رموز QR متجددة لكل محاضرة بصلاحية محددة، تمنع إعادة استخدام الرموز</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-green-600 text-sm">اقرأ المزيد <i class="fas fa-arrow-left mr-1"></i></span>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="feature-card bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-location-dot text-white text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">تحديد الموقع الجغرافي</h3>
                <p class="text-gray-600">التحقق من تواجد الطالب داخل الحرم الجامعي أو القاعة الدراسية</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-purple-600 text-sm">اقرأ المزيد <i class="fas fa-arrow-left mr-1"></i></span>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="feature-card bg-gradient-to-br from-orange-50 to-amber-50 p-8 rounded-2xl text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-line text-white text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">تقارير متقدمة</h3>
                <p class="text-gray-600">تقارير تحليلية لحضور الطلاب مع رسوم بيانية وإمكانية التصدير PDF/Excel</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-orange-600 text-sm">اقرأ المزيد <i class="fas fa-arrow-left mr-1"></i></span>
                </div>
            </div>

            <!-- Feature 5 -->
            <div class="feature-card bg-gradient-to-br from-red-50 to-rose-50 p-8 rounded-2xl text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bell text-white text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">إشعارات فورية</h3>
                <p class="text-gray-600">إشعارات واتساب وبريد إلكتروني للطلاب وأولياء الأمور عند الغياب أو التأخير</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-red-600 text-sm">اقرأ المزيد <i class="fas fa-arrow-left mr-1"></i></span>
                </div>
            </div>

            <!-- Feature 6 -->
            <div class="feature-card bg-gradient-to-br from-teal-50 to-cyan-50 p-8 rounded-2xl text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-shield-alt text-white text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">كشف التزوير</h3>
                <p class="text-gray-600">تقنيات متقدمة لكشف الصور والفيديوهات المزورة، لضمان مصداقية التسجيل</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-teal-600 text-sm">اقرأ المزيد <i class="fas fa-arrow-left mr-1"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="bg-gray-50 py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">كيف يعمل النظام؟</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-primary to-accent mx-auto rounded-full"></div>
            <p class="text-xl text-gray-600 mt-4">ثلاث خطوات بسيطة لتسجيل الحضور</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center group">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-r from-primary to-accent rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-4xl font-bold text-white">1</span>
                    </div>
                    <div class="absolute top-1/2 -right-10 hidden lg:block text-3xl text-gray-300 group-hover:text-primary transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">تسجيل الدخول</h3>
                <p class="text-gray-600">سجل دخولك باستخدام البريد الإلكتروني وكلمة المرور أو بصمة الوجه</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center group">
                <div class="relative">
                    <div class="w-32 h-32 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-4xl font-bold text-white">2</span>
                    </div>
                    <div class="absolute top-1/2 -right-10 hidden lg:block text-3xl text-gray-300 group-hover:text-green-500 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">مسح QR Code</h3>
                <p class="text-gray-600">امسح رمز QR الخاص بالمحاضرة باستخدام كاميرا جهازك</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center group">
                <div class="w-32 h-32 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span class="text-4xl font-bold text-white">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">تسجيل الحضور</h3>
                <p class="text-gray-600">يتم تسجيل حضورك تلقائياً مع تحديد الوقت والحالة</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section with Counter Animation -->
<div class="bg-gradient-to-r from-primary to-accent py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            <div class="stat-card">
                <div class="stat-number text-5xl font-bold mb-2 counter" data-target="10">0</div>
                <div class="text-white/90">جامعة فلسطينية</div>
                <div class="text-white/60 text-sm mt-1">+ شريك معنا</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-5xl font-bold mb-2 counter" data-target="50">0</div>
                <div class="text-white/90">طالب مسجل</div>
                <div class="text-white/60 text-sm mt-1">+ ألف</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-5xl font-bold mb-2 counter" data-target="100">0</div>
                <div class="text-white/90">تسجيل حضور</div>
                <div class="text-white/60 text-sm mt-1">+ ألف</div>
            </div>
            <div class="stat-card">
                <div class="stat-number text-5xl font-bold mb-2 counter" data-target="99">0</div>
                <div class="text-white/90">دقة التعرف</div>
                <div class="text-white/60 text-sm mt-1">%</div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="bg-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">ماذا يقولون عنا؟</h2>
            <div class="w-24 h-1 bg-gradient-to-r from-primary to-accent mx-auto rounded-full"></div>
            <p class="text-xl text-gray-600 mt-4">آراء المستخدمين عن تجربتهم مع النظام</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-primary to-accent rounded-full flex items-center justify-center text-white font-bold text-xl">
                        أ
                    </div>
                    <div class="mr-3">
                        <h4 class="font-bold text-gray-900">أحمد محمد</h4>
                        <p class="text-sm text-gray-500">طالب في جامعة فلسطين</p>
                    </div>
                </div>
                <p class="text-gray-600">"النظام سهل جداً في الاستخدام، وفر علي وقت كبير بدل التوقيع الورقي. بصمة الوجه دقيقة جداً"</p>
                <div class="mt-3 text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        د
                    </div>
                    <div class="mr-3">
                        <h4 class="font-bold text-gray-900">د. سامي الحسن</h4>
                        <p class="text-sm text-gray-500">محاضر في جامعة القدس</p>
                    </div>
                </div>
                <p class="text-gray-600">"ميزة متابعة الحضور المباشر ساعدتني في معرفة الغائبين فوراً، وتقارير الحضور ممتازة"</p>
                <div class="mt-3 text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        م
                    </div>
                    <div class="mr-3">
                        <h4 class="font-bold text-gray-900">مريم خالد</h4>
                        <p class="text-sm text-gray-500">مسؤولة نظم معلومات</p>
                    </div>
                </div>
                <p class="text-gray-600">"النظام متكامل وسهل الإدارة، لوحة تحكم الأدمن شاملة وقوية. أوصي به لأي مؤسسة تعليمية"</p>
                <div class="mt-3 text-yellow-400">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-gray-900 to-gray-800 py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
    <div class="max-w-4xl mx-auto text-center px-4 relative z-10">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-4 animate__animated animate__pulse animate__infinite">جاهز لتطوير جامعتك؟</h2>
        <p class="text-xl text-gray-300 mb-8">انضم إلى الجامعات الفلسطينية التي تستخدم نظام الحضور الذكي</p>
        @guest
            <a href="{{ route('register') }}" class="btn-primary text-white px-10 py-4 rounded-lg text-lg font-semibold inline-flex items-center gap-2 hover:scale-105 transition-transform duration-300">
                <i class="fas fa-rocket"></i>
                سجل الآن مجاناً
                <i class="fas fa-arrow-left group-hover:translate-x-1 transition-transform"></i>
            </a>
        @endguest
        <p class="text-gray-400 text-sm mt-6">* لا تحتاج إلى بطاقة ائتمان • دعم فني 24/7</p>
    </div>
</div>

<!-- GSAP Animations Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize GSAP
        gsap.registerPlugin(ScrollTrigger, TextPlugin);

        // Hero Section Animation
        gsap.from('.animate__animated', {
            duration: 1,
            y: 50,
            opacity: 0,
            stagger: 0.2
        });

        // Feature Cards Animation on Scroll
        gsap.utils.toArray('.feature-card').forEach(card => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: 'top 80%',
                    end: 'bottom 20%',
                    toggleActions: 'play none none reverse'
                },
                duration: 0.8,
                y: 50,
                opacity: 0,
                scale: 0.9
            });
        });

        // How It Works Steps Animation
        gsap.utils.toArray('.text-center.group').forEach((step, index) => {
            gsap.from(step, {
                scrollTrigger: {
                    trigger: step,
                    start: 'top 85%',
                },
                duration: 0.6,
                x: index === 0 ? -50 : (index === 2 ? 50 : 0),
                opacity: 0,
                delay: index * 0.2
            });
        });

        // Counter Animation
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            let current = 0;
            const increment = target / 50;

            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    if (target < 100) {
                        counter.innerText = Math.ceil(current) + (target === 99 ? '' : '');
                    } else {
                        counter.innerText = Math.floor(current) + 'K';
                    }
                    setTimeout(updateCounter, 40);
                } else {
                    counter.innerText = target + (target === 99 ? '%' : (target > 99 ? 'K' : ''));
                }
            };

            // Trigger counter when element is visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            observer.observe(counter);
        });

        // Testimonials Animation
        gsap.utils.toArray('.bg-gradient-to-br').forEach((testimonial, index) => {
            gsap.from(testimonial, {
                scrollTrigger: {
                    trigger: testimonial,
                    start: 'top 85%',
                },
                duration: 0.6,
                y: 30,
                opacity: 0,
                delay: index * 0.15
            });
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-bg');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endsection
