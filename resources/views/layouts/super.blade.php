<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin') | Smart Attendance SaaS</title>

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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Three.js Canvas Container */
        #three-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        /* Main Content */
        .main-content {
            position: relative;
            z-index: 10;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: rgba(15, 12, 41, 0.95);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            transform: translateX(0);
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .sidebar.collapsed {
            transform: translateX(280px);
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed;
            right: 290px;
            top: 20px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 101;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .sidebar-toggle.collapsed {
            right: 20px;
        }

        .sidebar-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        /* Nav Links */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 8px 16px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            transition: width 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            width: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            transform: translateX(-5px);
        }

        .nav-link i {
            width: 24px;
            margin-left: 12px;
            font-size: 1.2rem;
        }

        /* Stats Cards */
       .stat-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
}
.stat-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(102, 126, 234, 0.5);
    box-shadow: 0 20px 35px -10px rgba(0,0,0,0.3);
}
    .chart-container {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

      .glass-table {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    overflow: hidden;
}

.glass-table th {
    background: rgba(102, 126, 234, 0.2);
    padding: 16px;
    font-weight: 600;
    color: white;
}

.glass-table td {
    padding: 12px 16px;
    color: rgba(255, 255, 255, 0.8);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.glass-table tr:hover td {
    background: rgba(255, 255, 255, 0.05);
}

/* ========== Buttons ========== */
.btn-primary-glass {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    padding: 10px 24px;
    border-radius: 12px;
    color: white;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary-glass:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 10px 20px;
    border-radius: 12px;
    color: white;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-glass:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: transparent;
    transform: translateY(-2px);
    color: white;
}

/* ========== Badges ========== */
.badge-active {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
}

.badge-trial {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
}

.badge-inactive {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
}


/* ========== Pagination ========== */
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 20px;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    background: rgba(255, 255, 255, 0.1);
    padding: 8px 14px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    transition: 0.3s;
}

.pagination .page-link:hover {
    background: rgba(102, 126, 234, 0.5);
}

.pagination .active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

        /* Buttons */
        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 12px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-glass:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: transparent;
            transform: translateY(-2px);
        }

        .btn-primary-glass {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary-glass:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 10px;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Chart Container */
        .chart-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Status Badges */
        .badge-active {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .badge-trial {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(280px);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-toggle {
                right: 20px;
            }
            .main-content {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>

<!-- Three.js Canvas -->
<div id="three-canvas"></div>

<!-- Sidebar Toggle -->
<div class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars text-white text-xl"></i>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="p-6 text-center border-b border-white/10">
        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-chart-line text-3xl text-white"></i>
        </div>
        <h2 class="text-white text-xl font-bold">Smart Attendance</h2>
        <p class="text-white/50 text-sm">Super Admin Portal</p>
    </div>

    <nav class="mt-6">
        <a href="{{ route('super.dashboard') }}" class="nav-link {{ request()->routeIs('super.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            <span>لوحة التحكم</span>
        </a>
        <a href="{{ route('super.organizations') }}" class="nav-link {{ request()->routeIs('super.organizations*') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span>المؤسسات</span>
        </a>
        <a href="{{ route('super.plans') }}" class="nav-link {{ request()->routeIs('super.plans*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            <span>الخطط والباقات</span>
        </a>
        <a href="{{ route('super.invoices') }}" class="nav-link">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>الفواتير</span>
        </a>
        <a href="{{ route('super.reports') }}" class="nav-link">
            <i class="fas fa-chart-line"></i>
            <span>التقارير المتقدمة</span>
        </a>
        <a href="{{ route('super.settings') }}" class="nav-link">
            <i class="fas fa-cog"></i>
            <span>إعدادات المنصة</span>
        </a>
    </nav>

    <div class="absolute bottom-6 left-0 right-0 px-6">
        <div class="bg-white/5 rounded-xl p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div class="mr-3 flex-1">
                    <p class="text-white text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-white/50 text-xs">Super Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white/50 hover:text-white transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content" style="margin-right: 280px; padding: 20px; min-height: 100vh;">
    @yield('content')
</div>

<script>


    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        sidebarToggle.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            mainContent.style.marginRight = '0';
        } else {
            mainContent.style.marginRight = '280px';
        }
    });
</script>

<!-- Three.js Scene -->
<script type="importmap">
    {
        "imports": {
            "three": "https://unpkg.com/three@0.128.0/build/three.module.js"
        }
    }
</script>

<script type="module">
    import * as THREE from 'three';
    import gsap from 'https://unpkg.com/gsap@3.12.5/index.js';

    // Setup Scene
    const scene = new THREE.Scene();
    scene.background = null;
    scene.fog = new THREE.FogExp2(0x0f0c29, 0.002);

    // Camera
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 2, 8);
    camera.lookAt(0, 0, 0);

    // Renderer
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    document.getElementById('three-canvas').appendChild(renderer.domElement);

    // Particles
    const particlesGeometry = new THREE.BufferGeometry();
    const particlesCount = 2000;
    const posArray = new Float32Array(particlesCount * 3);

    for (let i = 0; i < particlesCount * 3; i += 3) {
        posArray[i] = (Math.random() - 0.5) * 50;
        posArray[i+1] = (Math.random() - 0.5) * 30;
        posArray[i+2] = (Math.random() - 0.5) * 30 - 10;
    }

    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

    const particlesMaterial = new THREE.PointsMaterial({
        size: 0.05,
        color: 0x667eea,
        transparent: true,
        opacity: 0.5,
        blending: THREE.AdditiveBlending
    });

    const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particlesMesh);

    // Floating Orbs
    const orbGeometry = new THREE.SphereGeometry(0.3, 32, 32);

    const orbs = [];
    const orbColors = [0x667eea, 0x764ba2, 0xf093fb, 0xf5576c];

    for (let i = 0; i < 12; i++) {
        const material = new THREE.MeshStandardMaterial({
            color: orbColors[i % orbColors.length],
            emissive: orbColors[i % orbColors.length],
            emissiveIntensity: 0.3,
            metalness: 0.7,
            roughness: 0.3
        });
        const orb = new THREE.Mesh(orbGeometry, material);

        orb.userData = {
            speedX: (Math.random() - 0.5) * 0.005,
            speedY: (Math.random() - 0.5) * 0.005,
            speedZ: (Math.random() - 0.5) * 0.005,
            rangeX: (Math.random() - 0.5) * 8,
            rangeY: (Math.random() - 0.5) * 6,
            rangeZ: (Math.random() - 0.5) * 5 - 2,
            phase: Math.random() * Math.PI * 2
        };

        orb.position.x = orb.userData.rangeX;
        orb.position.y = orb.userData.rangeY;
        orb.position.z = orb.userData.rangeZ;

        scene.add(orb);
        orbs.push(orb);
    }

    // Lights
    const ambientLight = new THREE.AmbientLight(0x404040);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
    directionalLight.position.set(5, 10, 7);
    scene.add(directionalLight);

    const backLight = new THREE.PointLight(0x667eea, 0.5);
    backLight.position.set(-2, 1, -3);
    scene.add(backLight);

    const fillLight = new THREE.PointLight(0x764ba2, 0.5);
    fillLight.position.set(3, 2, 4);
    scene.add(fillLight);

    // Animation Loop
    let time = 0;

    function animate() {
        requestAnimationFrame(animate);
        time += 0.01;

        // Rotate particles
        particlesMesh.rotation.y = time * 0.05;
        particlesMesh.rotation.x = Math.sin(time * 0.1) * 0.1;

        // Animate orbs
        orbs.forEach(orb => {
            orb.position.x += Math.sin(time * orb.userData.speedX * 100) * 0.002;
            orb.position.y += Math.cos(time * orb.userData.speedY * 100) * 0.002;
            orb.position.z += Math.sin(time * orb.userData.speedZ * 100) * 0.002;

            // Pulse scale
            const scale = 1 + Math.sin(time * 2 + orb.userData.phase) * 0.1;
            orb.scale.set(scale, scale, scale);
        });

        // Gentle camera movement
        camera.position.x += (0 - camera.position.x) * 0.02;
        camera.position.y += (Math.sin(time * 0.2) * 0.1 - camera.position.y) * 0.02;
        camera.lookAt(0, 0, 0);

        renderer.render(scene, camera);
    }

    animate();

    // Handle resize
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    // GSAP Entrance Animations
    gsap.from('.stat-card', {
        duration: 0.8,
        y: 50,
        opacity: 0,
        stagger: 0.1,
        ease: 'back.out(1.2)',
        delay: 0.2
    });

    gsap.from('.glass-table', {
        duration: 0.8,
        y: 30,
        opacity: 0,
        delay: 0.5
    });

    gsap.from('.chart-container', {
        duration: 0.8,
        scale: 0.9,
        opacity: 0,
        delay: 0.6
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js"></script>
@stack('scripts')
</body>
</html>
