<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Organization;
use Illuminate\Http\Request;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // تخطي الـ middleware لطلبات API و Super Admin
        if ($request->is('api/*') || $request->is('super/*') || $request->is('login') || $request->is('register')) {
            return $next($request);
        }

        // استخراج subdomain من الـ URL
        $host = $request->getHost();
        $parts = explode('.', $host);
        $subdomain = $parts[0];

        // تجاهل طلبات الـ localhost و www
        if ($subdomain === 'localhost' || $subdomain === 'www' || $subdomain === '127.0.0.1') {
            return $next($request);
        }

        // البحث عن المؤسسة
        $organization = Organization::where('subdomain', $subdomain)
            ->where('is_active', true)
            ->first();

        if (!$organization) {
            abort(404, 'المؤسسة غير موجودة أو غير نشطة');
        }

        // التحقق من صلاحية الاشتراك
        if (!$organization->isSubscriptionActive()) {
            return redirect()->route('subscription.expired');
        }

        // ربط المؤسسة بالـ request
        $request->merge(['organization' => $organization]);
        $request->attributes->set('organization', $organization);

        return $next($request);
    }
}
