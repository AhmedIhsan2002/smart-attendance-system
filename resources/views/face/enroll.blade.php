@extends('layouts.app')

@section('title', 'تسجيل بصمة الوجه')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-face-smile ml-2"></i>
                    تسجيل بصمة الوجه
                </h1>
                <p class="text-white/80 mt-1">ضع وجهك أمام الكاميرا واضغط "التقاط وتسجيل"</p>
            </div>

            <div class="p-6">
                <!-- Video Element -->
                <div class="relative bg-gray-900 rounded-lg overflow-hidden mb-4">
                    <video id="video" class="w-full h-auto" autoplay muted playsinline></video>
                    <canvas id="canvas" class="hidden"></canvas>
                </div>

                <!-- Status Message -->
                <div id="status" class="text-center p-3 rounded-lg mb-4 bg-blue-100 text-blue-700">
                    <i class="fas fa-spinner fa-spin ml-2"></i>
                    جاري تحميل نموذج الذكاء الاصطناعي...
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 justify-center">
                    <button id="captureBtn" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition" disabled>
                        <i class="fas fa-camera ml-1"></i> التقاط وتسجيل
                    </button>
                    <button id="retryBtn" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition hidden">
                        <i class="fas fa-redo ml-1"></i> إعادة المحاولة
                    </button>
                </div>

                <!-- Preview -->
                <div id="preview" class="hidden mt-4 text-center">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">تم التسجيل بنجاح!</h3>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-4xl"></i>
                        <p class="mt-2">يمكنك الآن استخدام بصمة الوجه لتسجيل الدخول</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('student.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-right ml-1"></i> العودة للوحة التحكم
            </a>
        </div>

    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const retryBtn = document.getElementById('retryBtn');
    const statusDiv = document.getElementById('status');
    const previewDiv = document.getElementById('preview');

    let stream = null;
    let modelsLoaded = false;

    // تحميل نماذج face-api
    async function loadModels() {
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري تحميل نماذج الذكاء الاصطناعي...';

        await faceapi.nets.ssdMobilenetv1.loadFromUri('https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js/weights');
        await faceapi.nets.faceLandmark68Net.loadFromUri('https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js/weights');
        await faceapi.nets.faceRecognitionNet.loadFromUri('https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js/weights');

        modelsLoaded = true;
        statusDiv.innerHTML = '<i class="fas fa-check-circle ml-2"></i> النماذج جاهزة، يمكنك البدء';
        statusDiv.classList.remove('bg-blue-100', 'text-blue-700');
        statusDiv.classList.add('bg-green-100', 'text-green-700');
        captureBtn.disabled = false;

        startCamera();
    }

    // تشغيل الكاميرا
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> لا يمكن الوصول إلى الكاميرا';
            statusDiv.classList.remove('bg-blue-100', 'text-blue-700');
            statusDiv.classList.add('bg-red-100', 'text-red-700');
        }
    }

    // التقاط الصورة واستخراج ترميز الوجه
    captureBtn.addEventListener('click', async () => {
        if (!modelsLoaded) {
            alert('الرجاء الانتظار حتى تحميل النماذج');
            return;
        }

        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري معالجة الصورة...';

        // رسم الصورة على canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // كشف الوجه
        const detections = await faceapi.detectSingleFace(canvas).withFaceLandmarks().withFaceDescriptor();

        if (!detections) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> لم يتم العثور على وجه، حاول مرة أخرى';
            statusDiv.classList.remove('bg-blue-100', 'text-blue-700');
            statusDiv.classList.add('bg-red-100', 'text-red-700');
            return;
        }

        // رسم إطار حول الوجه
        ctx.strokeStyle = '#00ff00';
        ctx.lineWidth = 2;
        ctx.strokeRect(
            detections.detection.box.x,
            detections.detection.box.y,
            detections.detection.box.width,
            detections.detection.box.height
        );

        // إرسال الترميز إلى الخادم
        const descriptor = Array.from(detections.descriptor);

        try {
            const response = await fetch('{{ route("face.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ descriptor: descriptor })
            });

            const result = await response.json();

            if (result.success) {
                // إيقاف الكاميرا
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                video.classList.add('hidden');
                captureBtn.classList.add('hidden');
                previewDiv.classList.remove('hidden');
                statusDiv.innerHTML = '<i class="fas fa-check-circle ml-2"></i> تم تسجيل الوجه بنجاح!';
                statusDiv.classList.remove('bg-red-100', 'text-red-700');
                statusDiv.classList.add('bg-green-100', 'text-green-700');
            } else {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> فشل التسجيل: ' + result.message;
            }
        } catch (error) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> خطأ في الاتصال بالخادم';
        }
    });

    // إعادة المحاولة
    retryBtn.addEventListener('click', () => {
        location.reload();
    });

    // بدء التحميل
    loadModels();
</script>
@endsection
