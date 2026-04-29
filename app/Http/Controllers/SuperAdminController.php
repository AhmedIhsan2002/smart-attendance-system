<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    // تم إزالة __construct لأن الـ middleware يتم تعريفه في routes

   public function dashboard()
{
    // ✅ جلب إحصائيات حقيقية
    $stats = [
        'total_organizations' => Organization::count(),
        'active_organizations' => Organization::where('subscription_status', 'active')->count(),
        'trial_organizations' => Organization::where('subscription_status', 'trial')->count(),
        'suspended_organizations' => Organization::where('subscription_status', 'suspended')->count(),
        'total_users' => User::count(),
        'total_students' => User::where('role', 'student')->count(),
        'total_instructors' => User::where('role', 'instructor')->count(),
        'total_admins' => User::where('role', 'admin')->count(),
        'total_revenue' => $this->getTotalRevenue(),
        'monthly_recurring_revenue' => $this->getMRR(),
    ];

    // ✅ بيانات الرسم البياني للمؤسسات (آخر 6 أشهر)
    $chartData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i);
        $count = Organization::whereYear('created_at', $month->year)
                             ->whereMonth('created_at', $month->month)
                             ->count();
        $chartData['months'][] = $month->format('M Y');
        $chartData['counts'][] = $count;
    }

    $recentOrganizations = Organization::with('plan', 'owner')->latest()->take(10)->get();
    $recentUsers = User::latest()->take(10)->get();

    return view('super.dashboard', compact('stats', 'chartData', 'recentOrganizations', 'recentUsers'));
}
    public function organizations()
    {
        $organizations = Organization::with('plan', 'owner')->paginate(20);
        return view('super.organizations.index', compact('organizations'));
    }

    public function createOrganization()
    {
        $plans = Plan::active()->get();
        return view('super.organizations.create', compact('plans'));
    }

  public function storeOrganization(Request $request)
{
    try {
        // ✅ عرض البيانات
        \Log::info('بيانات الطلب:', $request->all());

        // ✅ التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'subdomain' => 'required|string|unique:organizations,subdomain',
            'email' => 'required|email|unique:organizations,email',
            'plan_id' => 'required|exists:plans,id',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:8',
        ]);

        // ✅ إنشاء المؤسسة مباشرة
        $organization = new Organization();
        $organization->name = $request->name;
        $organization->name_ar = $request->name_ar;
        $organization->subdomain = $request->subdomain;
        $organization->email = $request->email;
        $organization->plan_id = $request->plan_id;
        $organization->subscription_status = 'trial';
        $organization->trial_ends_at = now()->addDays(14);
        $organization->is_active = true;
        $organization->max_students = 100;
        $organization->max_instructors = 10;
        $organization->max_courses = 20;

        $organization->save();

        \Log::info('Organization ID: ' . $organization->id);

        // ✅ إنشاء المستخدم الأدمن
        $admin = new User();
        $admin->organization_id = $organization->id;
        $admin->name = $request->admin_name;
        $admin->email = $request->admin_email;
        $admin->password = bcrypt($request->admin_password);
        $admin->role = 'admin';
        $admin->is_active = true;
        $admin->save();

        \Log::info('Admin ID: ' . $admin->id);

        $organization->owner_id = $admin->id;
        $organization->save();

        return redirect()->route('super.organizations')
            ->with('success', 'تم إنشاء المؤسسة بنجاح!');

    } catch (\Exception $e) {
        \Log::error('ERROR: ' . $e->getMessage());
        return back()->with('error', 'خطأ: ' . $e->getMessage())->withInput();
    }
}
    public function editOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $plans = Plan::active()->get();
        return view('super.organizations.edit', compact('organization', 'plans'));
    }

    public function updateOrganization(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'email' => 'required|email|unique:organizations,email,' . $id,
            'plan_id' => 'required|exists:plans,id',
            'subscription_status' => 'required|in:trial,active,expired,cancelled,suspended',
        ]);

        $organization->update($request->only([
            'name', 'name_ar', 'email', 'plan_id', 'subscription_status'
        ]));

        // تحديث الحدود بناءً على الخطة الجديدة
        $plan = Plan::find($request->plan_id);
        $organization->max_students = $plan->max_students;
        $organization->max_instructors = $plan->max_instructors;
        $organization->max_courses = $plan->max_courses;
        $organization->save();

        return redirect()->route('super.organizations')
            ->with('success', 'تم تحديث المؤسسة بنجاح');
    }

    public function suspendOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->subscription_status = 'suspended';
        $organization->is_active = false;
        $organization->save();

        return redirect()->back()->with('success', 'تم تعليق المؤسسة بنجاح');
    }

    public function activateOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->subscription_status = 'active';
        $organization->is_active = true;
        $organization->activated_at = now();
        $organization->save();

        return redirect()->back()->with('success', 'تم تفعيل المؤسسة بنجاح');
    }

    public function deleteOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return redirect()->route('super.organizations')
            ->with('success', 'تم حذف المؤسسة بنجاح');
    }

    public function plans()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('super.plans.index', compact('plans'));
    }

    public function createPlan()
    {
        return view('super.plans.create');
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'required|string|unique:plans|alpha_dash',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'max_instructors' => 'required|integer|min:1',
            'max_courses' => 'required|integer|min:1',
        ]);

        Plan::create($request->all());

        return redirect()->route('super.plans')->with('success', 'تم إنشاء الخطة بنجاح');
    }

    public function editPlan($id)
    {
        $plan = Plan::findOrFail($id);
        return view('super.plans.edit', compact('plan'));
    }

    public function updatePlan(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'required|string|alpha_dash|unique:plans,slug,' . $id,
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'max_instructors' => 'required|integer|min:1',
            'max_courses' => 'required|integer|min:1',
        ]);

        $plan->update($request->all());

        return redirect()->route('super.plans')->with('success', 'تم تحديث الخطة بنجاح');
    }

    public function deletePlan($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return redirect()->route('super.plans')->with('success', 'تم حذف الخطة بنجاح');
    }

    private function getTotalRevenue()
    {
        return \App\Models\Invoice::where('status', 'paid')->sum('amount');
    }

    private function getMRR()
    {
        return \App\Models\Invoice::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');
    }
}
