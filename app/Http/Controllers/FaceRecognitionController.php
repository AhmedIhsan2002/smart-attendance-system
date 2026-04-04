<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FaceRecognitionController extends Controller
{
    // صفحة تسجيل الوجه
    public function enroll()
    {
        return view('face.enroll');
    }

    // صفحة التحقق من الوجه لتسجيل الدخول
    public function verify()
    {
        return view('face.verify');
    }

    // حفظ بيانات الوجه بعد التسجيل
    public function saveDescriptor(Request $request)
    {
        $request->validate([
            'descriptor' => 'required|array'
        ]);

        $user = Auth::user();
        $user->face_descriptor = json_encode($request->descriptor);
        $user->face_enrolled = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الوجه بنجاح'
        ]);
    }

    // التحقق من الوجه وتسجيل الدخول
    public function checkFace(Request $request)
    {
        $request->validate([
            'descriptor' => 'required|array'
        ]);

        $currentDescriptor = $request->descriptor;
        $users = User::where('face_enrolled', true)->get();

        foreach ($users as $user) {
            $savedDescriptor = json_decode($user->face_descriptor, true);

            // حساب المسافة بين الترميزين (Euclidean distance)
            $distance = $this->calculateDistance($currentDescriptor, $savedDescriptor);

            // إذا كان التشابه عالياً (المسافة صغيرة)
            if ($distance < 0.6) { // عتبة التشابه
                Auth::login($user);
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'message' => 'تم تسجيل الدخول بنجاح',
                    'redirect' => route('dashboard')
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'لم يتم التعرف على الوجه'
        ], 401);
    }

    // حساب المسافة بين ترميزين
    private function calculateDistance($desc1, $desc2)
    {
        $sum = 0;
        for ($i = 0; $i < count($desc1); $i++) {
            $sum += pow($desc1[$i] - $desc2[$i], 2);
        }
        return sqrt($sum);
    }

    // حذف بصمة الوجه
    public function delete(Request $request)
    {
        $user = Auth::user();
        $user->face_descriptor = null;
        $user->face_enrolled = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف بصمة الوجه بنجاح'
        ]);
    }
}
