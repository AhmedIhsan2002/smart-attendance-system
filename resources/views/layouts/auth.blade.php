<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام الحضور الذكي') | {{ config('app.name', 'Smart Attendance') }}</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .bg-animation .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 8s infinite ease-in-out;
        }

        .bg-animation .circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .bg-animation .circle:nth-child(2) {
            width: 500px;
            height: 500px;
            bottom: -200px;
            right: -200px;
            animation-delay: 2s;
        }

        .bg-animation .circle:nth-child(3) {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 4s;
        }

        .bg-animation .circle:nth-child(4) {
            width: 150px;
            height: 150px;
            top: 20%;
            right: 15%;
            animation-delay: 1s;
        }

        .bg-animation .circle:nth-child(5) {
            width: 250px;
            height: 250px;
            bottom: 10%;
            left: 10%;
            animation-delay: 3s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0.3;
            }
            33% {
                transform: translateY(-30px) translateX(20px);
                opacity: 0.6;
            }
            66% {
                transform: translateY(30px) translateX(-20px);
                opacity: 0.4;
            }
        }

        /* Auth Card */
        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
            z-index: 10;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 300% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Input Fields */
        .input-group {
            position: relative;
            margin-bottom: 24px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .input-group input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .input-group label {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: all 0.3s ease;
            pointer-events: none;
            font-size: 16px;
        }

        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            top: -10px;
            left: 12px;
            font-size: 12px;
            background: white;
            padding: 0 6px;
            color: #667eea;
        }

        /* Button */
        .btn-auth {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .btn-auth::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-auth:hover::before {
            width: 300px;
            height: 300px;
        }

        /* Face Login Button */
        .btn-face {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-face:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
        }

        /* Floating animation */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Ripple effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .ripple:active:after {
            width: 200px;
            height: 200px;
        }

        /* Error/Success Messages */
        .alert-message {
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8 floating">
                <div class="inline-block p-4 bg-white/10 backdrop-blur-lg rounded-2xl mb-4">
                    <i class="fas fa-face-recognition text-5xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">نظام الحضور الذكي</h1>
                <p class="text-white/80 text-sm mt-1">Smart Attendance System</p>
            </div>

            <!-- Auth Card -->
            <div class="auth-card animate__animated animate__fadeInUp">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-white/70 text-sm">
                <p>© 2026نظام الحضور الذكي | جميع الحقوق محفوظة</p>
            </div>
        </div>
    </div>

    <script>
        // GSAP Animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate card entrance
            gsap.from('.auth-card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                scale: 0.95,
                ease: 'back.out(1.2)'
            });

            // Animate logo
            gsap.from('.floating', {
                duration: 0.6,
                y: -30,
                opacity: 0,
                delay: 0.2
            });

            // Animate form elements
            gsap.from('.input-group', {
                duration: 0.5,
                x: -30,
                opacity: 0,
                stagger: 0.1,
                delay: 0.4
            });

            gsap.from('.btn-auth, .btn-face, .flex.items-center', {
                duration: 0.5,
                y: 20,
                opacity: 0,
                stagger: 0.1,
                delay: 0.6
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn-auth, .btn-face').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.classList.add('ripple-effect');
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
    </script>
</body>
</html>
