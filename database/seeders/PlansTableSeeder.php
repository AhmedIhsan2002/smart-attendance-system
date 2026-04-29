<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansTableSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'name_ar' => 'أساسي',
                'slug' => 'basic',
                'price_monthly' => 49,
                'price_yearly' => 490,
                'max_students' => 100,
                'max_instructors' => 5,
                'max_courses' => 10,
                'max_departments' => 3,
                'has_face_recognition' => false,
                'has_api_access' => false,
                'has_advanced_reports' => false,
                'sort_order' => 1,
                'features' => [
                    'تسجيل الحضور بـ QR Code',
                    'تقارير أساسية',
                    'دعم البريد الإلكتروني',
                    'لوحات تحكم للطلاب والدكاترة',
                ]
            ],
            [
                'name' => 'Pro',
                'name_ar' => 'احترافي',
                'slug' => 'pro',
                'price_monthly' => 149,
                'price_yearly' => 1490,
                'max_students' => 500,
                'max_instructors' => 20,
                'max_courses' => 50,
                'max_departments' => 10,
                'has_face_recognition' => true,
                'has_api_access' => true,
                'has_advanced_reports' => true,
                'sort_order' => 2,
                'features' => [
                    'تسجيل الحضور بـ QR Code',
                    'بصمة الوجه',
                    'تقارير متقدمة مع رسوم بيانية',
                    'API للتكامل',
                    'دعم 24/7',
                    'تصدير Excel متقدم',
                ]
            ],
            [
                'name' => 'Enterprise',
                'name_ar' => 'مؤسسات',
                'slug' => 'enterprise',
                'price_monthly' => 499,
                'price_yearly' => 4990,
                'max_students' => 5000,
                'max_instructors' => 100,
                'max_courses' => 200,
                'max_departments' => 30,
                'has_face_recognition' => true,
                'has_api_access' => true,
                'has_advanced_reports' => true,
                'sort_order' => 3,
                'features' => [
                    'جميع ميزات Pro',
                    'تخصيص كامل للنظام',
                    'دعم أولوية 24/7',
                    'تكامل مع أنظمة الجامعة',
                    'تحليلات تنبؤية بالذكاء الاصطناعي',
                    'لوحة تحكم مخصصة',
                ]
            ],
            [
                'name' => 'University',
                'name_ar' => 'جامعي',
                'slug' => 'university',
                'price_monthly' => 999,
                'price_yearly' => 9990,
                'max_students' => 99999,
                'max_instructors' => 999,
                'max_courses' => 999,
                'max_departments' => 100,
                'has_face_recognition' => true,
                'has_api_access' => true,
                'has_advanced_reports' => true,
                'sort_order' => 4,
                'features' => [
                    'جميع ميزات Enterprise',
                    'عقود مخصصة',
                    'خادم مخصص',
                    'تدريب للموظفين',
                    'دعم على مدار الساعة',
                ]
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
