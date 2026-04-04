# 🎓 نظام الحضور والانصراف الذكي للجامعات الفلسطينية

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?logo=mysql)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?logo=tailwindcss)
![License](https://img.shields.io/badge/License-MIT-green)

## 📋 نبذة عن المشروع

نظام متكامل لإدارة الحضور والانصراف في الجامعات الفلسطينية باستخدام تقنيات الذكاء الاصطناعي والتعرف على الوجه و QR Code. يهدف النظام إلى استبدال الطرق التقليدية (التوقيع الورقي) بنظام رقمي أكثر دقة وأماناً وسهولة في الاستخدام.

## ✨ المميزات الرئيسية

### 👨‍🎓 للطلاب
- لوحة تحكم شخصية تعرض المواد المسجلة ونسب الحضور
- تسجيل الحضور عبر QR Code أو بصمة الوجه
- سجل كامل للحضور والغياب
- إشعارات وتنبيهات
- ملف شخصي لتحديث البيانات

### 👨‍🏫 للدكاترة
- إدارة المواد الدراسية
- بدء محاضرات جديدة مع توليد QR Code ديناميكي (صلاحية 15 دقيقة)
- متابعة حضور الطلاب في الوقت الفعلي
- تقارير وتحليلات للحضور

### 👑 للأدمن
- إدارة المستخدمين (طلاب/دكاترة/أدمن)
- إدارة الكليات والأقسام
- إدارة المواد الدراسية
- إحصائيات عامة للنظام

### 📊 التقارير
- فلترة حسب المادة والتاريخ
- إحصائيات عامة مع رسوم بيانية تفاعلية (Chart.js)
- تصدير التقارير إلى Excel

### 🤖 الذكاء الاصطناعي
- تسجيل بصمة الوجه عبر الكاميرا
- التحقق من الوجه لتسجيل الدخول
- مكتبة face-api.js (تعمل في المتصفح بدون Python)

## 🛠 التقنيات المستخدمة

| التقنية | الاستخدام |
|---------|-----------|
| **Laravel 12** | إطار العمل الرئيسي (Backend) |
| **MySQL** | قاعدة البيانات |
| **Tailwind CSS** | تصميم الواجهات |
| **GSAP + Animate.css** | حركات وتأثيرات احترافية |
| **Chart.js** | الرسوم البيانية |
| **face-api.js** | التعرف على الوجه (AI) |
| **Simple QRCode** | توليد QR Codes |
| **Laravel Breeze** | نظام المصادقة |

## 📊 قاعدة البيانات

| الجدول | الوظيفة |
|--------|---------|
| `users` | المستخدمين (طلاب/دكاترة/أدمن) |
| `colleges` | الكليات |
| `departments` | الأقسام الأكاديمية |
| `courses` | المواد الدراسية |
| `lecture_sessions` | جلسات المحاضرات |
| `attendances` | سجل الحضور |
| `notifications` | الإشعارات |
| `logs` | سجل العمليات |
| `course_user` | ربط الطلاب بالمواد |

## 📸 لقطات من المشروع

| الصفحة | المعاينة |
|--------|----------|
| الصفحة الرئيسية | ![Home](screenshots/home.png) |
| لوحة تحكم الطالب | ![Student Dashboard](screenshots/student-dashboard.png) |
| لوحة تحكم الدكتور | ![Instructor Dashboard](screenshots/instructor-dashboard.png) |
| لوحة تحكم الأدمن | ![Admin Dashboard](screenshots/admin-dashboard.png) |
| تسجيل بصمة الوجه | ![Face Recognition](screenshots/face-enroll.png) |
| التقارير | ![Reports](screenshots/reports.png) |

## 🚀 كيفية تشغيل المشروع محلياً

### المتطلبات الأساسية
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js & NPM
- XAMPP / WAMP / Laragon

### خطوات التثبيت

```bash
# 1. استنساخ المشروع
git clone https://github.com/your-username/smart-attendance-system.git
cd smart-attendance-system

# 2. تثبيت الحزم
composer install
npm install

# 3. نسخ ملف البيئة
cp .env.example .env

# 4. إنشاء مفتاح التطبيق
php artisan key:generate

# 5. إعداد قاعدة البيانات
php artisan migrate --seed

# 6. تشغيل الـ Seeder للبيانات التجريبية
php artisan db:seed --class=TestDataSeeder

# 7. تشغيل السيرفر
php artisan serve
npm run dev
