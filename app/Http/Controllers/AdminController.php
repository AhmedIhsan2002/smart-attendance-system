<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Course;
use App\Models\Session;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminController extends Controller
{
    // لوحة التحكم الرئيسية
    public function dashboard()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_courses' => Course::count(),
            'total_colleges' => College::count(),
            'total_departments' => Department::count(),
            'today_attendance' => Attendance::whereDate('check_in_time', Carbon::today())->count(),
            'active_sessions' => Session::where('status', 'ongoing')->count(),
        ];

        // آخر 5 مستخدمين مسجلين
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        // آخر 5 تسجيلات حضور
        $recentAttendances = Attendance::with(['user', 'session.course'])
                                       ->orderBy('created_at', 'desc')
                                       ->take(5)
                                       ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentAttendances'));
    }

    // ========== إدارة المستخدمين ==========

    public function users(Request $request)
    {
        $query = User::query();

        // فلترة حسب الدور
        if ($request->role && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        // بحث
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:student,instructor,admin',
            'student_id' => 'nullable|unique:users',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:student,instructor,admin',
            'student_id' => 'nullable|unique:users,student_id,' . $id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // لا يمكن حذف الأدمن الوحيد
        if ($user->role == 'admin' && User::where('role', 'admin')->count() == 1) {
            return redirect()->back()->with('error', 'لا يمكن حذف الأدمن الوحيد في النظام');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'تم تغيير حالة المستخدم بنجاح');
    }

    // ========== إدارة الكليات ==========

    public function colleges()
    {
        $colleges = College::withCount('departments')->orderBy('name')->paginate(10);
        return view('admin.colleges.index', compact('colleges'));
    }

    public function createCollege()
    {
        return view('admin.colleges.create');
    }

    public function storeCollege(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:colleges',
            'description' => 'nullable|string',
            'dean_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'building' => 'nullable|string|max:100',
        ]);

        College::create($request->all());

        return redirect()->route('admin.colleges')->with('success', 'تم إضافة الكلية بنجاح');
    }

    public function editCollege($id)
    {
        $college = College::findOrFail($id);
        return view('admin.colleges.edit', compact('college'));
    }

    public function updateCollege(Request $request, $id)
    {
        $college = College::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:colleges,code,' . $id,
            'description' => 'nullable|string',
            'dean_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'building' => 'nullable|string|max:100',
        ]);

        $college->update($request->all());

        return redirect()->route('admin.colleges')->with('success', 'تم تحديث الكلية بنجاح');
    }

    public function deleteCollege($id)
    {
        $college = College::findOrFail($id);

        // التحقق من وجود أقسام تابعة
        if ($college->departments()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف الكلية لأنها تحتوي على أقسام');
        }

        $college->delete();

        return redirect()->route('admin.colleges')->with('success', 'تم حذف الكلية بنجاح');
    }

    // ========== إدارة الأقسام ==========

    public function departments(Request $request)
    {
        $query = Department::with('college');

        if ($request->college_id) {
            $query->where('college_id', $request->college_id);
        }

        $departments = $query->orderBy('name')->paginate(10);
        $colleges = College::all();

        return view('admin.departments.index', compact('departments', 'colleges'));
    }

    public function createDepartment()
    {
        $colleges = College::all();
        return view('admin.departments.create', compact('colleges'));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments',
            'description' => 'nullable|string',
            'head_name' => 'nullable|string|max:255',
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function editDepartment($id)
    {
        $department = Department::findOrFail($id);
        $colleges = College::all();
        return view('admin.departments.edit', compact('department', 'colleges'));
    }

    public function updateDepartment(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:departments,code,' . $id,
            'description' => 'nullable|string',
            'head_name' => 'nullable|string|max:255',
        ]);

        $department->update($request->all());

        return redirect()->route('admin.departments')->with('success', 'تم تحديث القسم بنجاح');
    }

    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);

        // التحقق من وجود مواد تابعة
        if ($department->courses()->count() > 0) {
            return redirect()->back()->with('error', 'لا يمكن حذف القسم لأنه يحتوي على مواد');
        }

        $department->delete();

        return redirect()->route('admin.departments')->with('success', 'تم حذف القسم بنجاح');
    }

    // ========== إدارة المواد ==========

    public function courses(Request $request)
    {
        $query = Course::with(['instructor', 'department']);

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $courses = $query->orderBy('name')->paginate(10);
        $departments = Department::with('college')->get();

        return view('admin.courses.index', compact('courses', 'departments'));
    }

    public function createCourse()
    {
        $instructors = User::where('role', 'instructor')->get();
        $departments = Department::with('college')->get();
        return view('admin.courses.create', compact('instructors', 'departments'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses',
            'description' => 'nullable|string',
            'instructor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'credit_hours' => 'required|integer|min:1|max:6',
            'semester' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string',
            'max_students' => 'nullable|integer',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses')->with('success', 'تم إضافة المادة بنجاح');
    }

    public function editCourse($id)
    {
        $course = Course::findOrFail($id);
        $instructors = User::where('role', 'instructor')->get();
        $departments = Department::with('college')->get();
        return view('admin.courses.edit', compact('course', 'instructors', 'departments'));
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code,' . $id,
            'description' => 'nullable|string',
            'instructor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'credit_hours' => 'required|integer|min:1|max:6',
            'semester' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string',
            'max_students' => 'nullable|integer',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses')->with('success', 'تم تحديث المادة بنجاح');
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'تم حذف المادة بنجاح');
    }

    // ========== إعدادات النظام ==========

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        // يمكنك حفظ الإعدادات في جدول settings
        return redirect()->back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
