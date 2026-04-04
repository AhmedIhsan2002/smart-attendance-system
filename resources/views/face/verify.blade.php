@extends('layouts.app')

@section('title', 'التحقق من بصمة الوجه')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-face-smile ml-2"></i>
                    التحقق من بصمة الوجه
                </h1>
                <p class="text-white/80 mt-1">ضع وجهك أمام الكاميرا للتحقق من هويتك</p>
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
                    <button id="verifyBtn" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition" disabled>
                        <i class="fas fa-camera ml-1"></i> التحقق من الوجه
                    </button>
                    <button id="retryBtn" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition hidden">
                        <i class="fas fa-redo ml-1"></i> إعادة المحاولة
                    </button>
                </div>

                <!-- Result -->
                <div id="result" class="hidden mt-4 text-center p-4 rounded-lg"></div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-arrow-right ml-1"></i> العودة لتسجيل الدخول العادي
            </a>
        </div>

    </div>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const verifyBtn = document.getElementById('verifyBtn');
    const retryBtn = document.getElementById('retryBtn');
    const statusDiv = document.getElementById('status');
    const resultDiv = document.getElementById('result');

    let stream = null;
    let modelsLoaded = false;

    // تحميل نماذج face-api
    async function loadModels() {
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري تحميل نماذج الذكاء الاصطناعي...';

        try {
            // استخدام الرابط الصحيح لتحميل النماذج
            const MODEL_URL = 'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights';

            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);

            modelsLoaded = true;
            statusDiv.innerHTML = '<i class="fas fa-check-circle ml-2"></i> النماذج جاهزة، يمكنك البدء';
            statusDiv.classList.remove('bg-blue-100', 'text-blue-700');
            statusDiv.classList.add('bg-green-100', 'text-green-700');
            verifyBtn.disabled = false;

            startCamera();
        } catch (error) {
            console.error('Model loading error:', error);
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> خطأ في تحميل النماذج. تأكد من اتصالك بالإنترنت ثم حاول مرة أخرى';
            statusDiv.classList.remove('bg-blue-100', 'text-blue-700');
            statusDiv.classList.add('bg-red-100', 'text-red-700');
        }
    }

    // تشغيل الكاميرا
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            console.error('Camera error:', err);
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> لا يمكن الوصول إلى الكاميرا. يرجى السماح باستخدام الكاميرا';
            statusDiv.classList.remove('bg-blue-100', 'text-blue-700');
            statusDiv.classList.add('bg-red-100', 'text-red-700');
        }
    }

    // التحقق من الوجه
    verifyBtn.addEventListener('click', async () => {
        if (!modelsLoaded) {
            alert('الرجاء الانتظار حتى تحميل النماذج');
            return;
        }

        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري معالجة الصورة والتحقق...';
        verifyBtn.disabled = true;

        // رسم الصورة على canvas
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        try {
            // كشف الوجه
            const detections = await faceapi.detectSingleFace(canvas).withFaceLandmarks().withFaceDescriptor();

            if (!detections) {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> لم يتم العثور على وجه، حاول مرة أخرى';
                statusDiv.classList.remove('bg-blue-100', 'text-blue-700');
                statusDiv.classList.add('bg-red-100', 'text-red-700');
                verifyBtn.disabled = false;
                return;
            }

            // إرسال الترميز إلى الخادم للتحقق
            const descriptor = Array.from(detections.descriptor);

            const response = await fetch('{{ route("face.check") }}', {
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
                verifyBtn.classList.add('hidden');
                retryBtn.classList.remove('hidden');

                resultDiv.classList.remove('hidden');
                resultDiv.classList.add('bg-green-100', 'text-green-700');
                resultDiv.innerHTML = '<i class="fas fa-check-circle fa-2x mb-2"></i><br>' + result.message;
                statusDiv.innerHTML = '<i class="fas fa-check-circle ml-2"></i> تم التحقق بنجاح! جاري تحويلك...';

                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 2000);
            } else {
                resultDiv.classList.remove('hidden');
                resultDiv.classList.add('bg-red-100', 'text-red-700');
                resultDiv.innerHTML = '<i class="fas fa-times-circle fa-2x mb-2"></i><br>' + result.message;
                statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> فشل التحقق';
                verifyBtn.disabled = false;
            }
        } catch (error) {
            console.error('Detection error:', error);
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle ml-2"></i> خطأ في معالجة الصورة';
            verifyBtn.disabled = false;
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
