<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Course;
use App\Models\Session;
use App\Models\Attendance;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // التأكد من وجود مستخدمين
        $instructor = User::where('role', 'instructor')->first();
        if (!$instructor) {
            $instructor = User::create([
                'name' => 'Dr. Ahmed Al-Masri',
                'email' => 'dr.ahmed@university.ps',
                'password' => bcrypt('password'),
                'role' => 'instructor',
                'is_active' => true
            ]);
            $this->command->info('تم إنشاء الدكتور: dr.ahmed@university.ps');
        } else {
            $this->command->info('الدكتور موجود بالفعل');
        }

        $student = User::where('email', 'student@university.ps')->first();
        if (!$student) {
            $student = User::create([
                'name' => 'Mohammed Al-Ghazzawi',
                'email' => 'student@university.ps',
                'student_id' => '202410001',
                'password' => bcrypt('password'),
                'role' => 'student',
                'is_active' => true
            ]);
            $this->command->info('تم إنشاء الطالب: student@university.ps');
        } else {
            $this->command->info('الطالب موجود بالفعل');
        }

        // إنشاء كلية - استخدام firstOrCreate لتجنب التكرار
        $college = College::firstOrCreate(
            ['code' => 'ENG'],
            [
                'name' => 'Faculty of Engineering',
                'name_ar' => 'كلية الهندسة',
                'description' => 'كلية الهندسة وتكنولوجيا المعلومات',
                'dean_name' => 'Prof. Khalid Al-Sharif',
                'phone' => '08 1234567',
                'email' => 'engineering@university.ps',
                'building' => 'Building A'
            ]
        );
        $this->command->info('تم إنشاء/العثور على كلية الهندسة');

        // إنشاء قسم - استخدام firstOrCreate لتجنب التكرار
        $department = Department::firstOrCreate(
            ['code' => 'CS', 'college_id' => $college->id],
            [
                'college_id' => $college->id,
                'name' => 'Computer Science',
                'name_ar' => 'علوم الحاسوب',
                'description' => 'قسم علوم الحاسوب وتكنولوجيا المعلومات',
                'head_name' => 'Dr. Sami Al-Hassan'
            ]
        );
        $this->command->info('تم إنشاء/العثور على قسم علوم الحاسوب');

        // تعريف المواد
        $coursesData = [
            [
                'code' => 'WEB301',
                'name' => 'Web Development',
                'name_ar' => 'تطوير الويب',
                'description' => 'مقدمة في تطوير تطبيقات الويب باستخدام Laravel',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'location' => 'قاعة 101'
            ],
            [
                'code' => 'DB301',
                'name' => 'Database Systems',
                'name_ar' => 'أنظمة قواعد البيانات',
                'description' => 'تصميم وإدارة قواعد البيانات SQL',
                'start_time' => '11:00:00',
                'end_time' => '13:00:00',
                'location' => 'قاعة 102'
            ],
            [
                'code' => 'AI401',
                'name' => 'Artificial Intelligence',
                'name_ar' => 'الذكاء الاصطناعي',
                'description' => 'مقدمة في الذكاء الاصطناعي وتعلم الآلة',
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'location' => 'قاعة 103'
            ]
        ];

        // إنشاء المواد
        foreach ($coursesData as $courseData) {
            $course = Course::firstOrCreate(
                ['code' => $courseData['code']],
                [
                    'name' => $courseData['name'],
                    'name_ar' => $courseData['name_ar'],
                    'description' => $courseData['description'],
                    'instructor_id' => $instructor->id,
                    'department_id' => $department->id,
                    'credit_hours' => 3,
                    'semester' => 'Spring 2024',
                    'start_time' => $courseData['start_time'],
                    'end_time' => $courseData['end_time'],
                    'location' => $courseData['location'],
                    'is_active' => true
                ]
            );

            // ربط الطالب بالمادة (إذا لم يكن مرتبطاً)
            if (!$student->courses()->where('course_id', $course->id)->exists()) {
                $student->courses()->attach($course->id, ['status' => 'enrolled']);
                $this->command->info("تم ربط الطالب بمادة {$courseData['name_ar']}");
            } else {
                $this->command->info("الطالب مرتبط بالفعل بمادة {$courseData['name_ar']}");
            }

            // التحقق من وجود جلسات للمادة
            $existingSessions = Session::where('course_id', $course->id)->count();

            if ($existingSessions == 0) {
                // إنشاء جلسات سابقة للمادة (آخر 5 محاضرات)
                for ($i = 1; $i <= 5; $i++) {
                    $sessionDate = Carbon::today()->subDays($i * 7); // محاضرة كل أسبوع

                    $session = Session::create([
                        'course_id' => $course->id,
                        'date' => $sessionDate,
                        'start_time' => $courseData['start_time'],
                        'end_time' => $courseData['end_time'],
                        'qr_code' => uniqid() . '_' . $course->id . '_' . $i,
                        'qr_expires_at' => $sessionDate->copy()->setTimeFromTimeString($courseData['start_time'])->addMinutes(30),
                        'status' => 'completed',
                        'is_active' => false
                    ]);

                    // إنشاء حضور للطالب (80% حضور، المحاضرة الثالثة تأخير)
                    if ($i <= 4) {
                        $status = ($i == 3) ? 'late' : 'present';
                        $checkInTime = $sessionDate->copy()->setTimeFromTimeString($courseData['start_time']);

                        if ($i == 3) {
                            $checkInTime->addMinutes(15); // تأخير 15 دقيقة
                        }

                        Attendance::create([
                            'user_id' => $student->id,
                            'session_id' => $session->id,
                            'status' => $status,
                            'check_in_time' => $checkInTime,
                            'verification_method' => 'qr',
                            'is_verified' => true
                        ]);
                    }
                }
                $this->command->info("تم إنشاء جلسات لمادة {$courseData['name_ar']}");
            } else {
                $this->command->info("جلسات مادة {$courseData['name_ar']} موجودة بالفعل");
            }
        }

        // إنشاء محاضرة لليوم لمادة WEB301
        $todayCourse = Course::where('code', 'WEB301')->first();
        if ($todayCourse) {
            $todaySessionExists = Session::where('course_id', $todayCourse->id)
                                         ->where('date', Carbon::today())
                                         ->exists();

            if (!$todaySessionExists) {
                Session::create([
                    'course_id' => $todayCourse->id,
                    'date' => Carbon::today(),
                    'start_time' => '09:00:00',
                    'end_time' => '11:00:00',
                    'qr_code' => 'TODAY_' . uniqid() . '_' . time(),
                    'qr_expires_at' => Carbon::today()->setTime(11, 30, 0),
                    'status' => 'ongoing',
                    'is_active' => true
                ]);
                $this->command->info('تم إنشاء محاضرة اليوم لمادة تطوير الويب');
            } else {
                $this->command->info('محاضرة اليوم موجودة بالفعل');
            }
        }

        // إنشاء إشعارات للطالب إذا لم تكن موجودة
        $existingNotifications = $student->notifications()->count();

        if ($existingNotifications == 0) {
            $student->notifications()->createMany([
                [
                    'title' => 'تذكير بمحاضرة',
                    'message' => 'لديك محاضرة تطوير الويب اليوم الساعة 9:00 صباحاً في قاعة 101',
                    'type' => 'email',
                    'is_read' => false,
                    'sent_at' => now()
                ],
                [
                    'title' => 'تنبيه الغياب',
                    'message' => 'نسبة غيابك في مادة قواعد البيانات وصلت إلى 20%، يرجى الالتزام بالحضور',
                    'type' => 'whatsapp',
                    'is_read' => false,
                    'sent_at' => now()
                ],
                [
                    'title' => 'تغيير موعد المحاضرة',
                    'message' => 'تم تغيير موعد محاضرة الذكاء الاصطناعي يوم الأربعاء القادم إلى الساعة 2:00 مساءً',
                    'type' => 'email',
                    'is_read' => true,
                    'sent_at' => now()->subDays(2)
                ],
                [
                    'title' => 'تهانينا',
                    'message' => 'نسبة حضورك في مادة تطوير الويب 95%، استمر',
                    'type' => 'email',
                    'is_read' => false,
                    'sent_at' => now()->subDays(1)
                ]
            ]);
            $this->command->info('تم إنشاء الإشعارات للطالب');
        } else {
            $this->command->info('الإشعارات موجودة بالفعل');
        }

        $this->command->info('');
        $this->command->info('=====================================');
        $this->command->info('✅ تم إضافة البيانات التجريبية بنجاح!');
        $this->command->info('=====================================');
        $this->command->info('البريد الإلكتروني للطالب: student@university.ps');
        $this->command->info('كلمة المرور: password');
        $this->command->info('البريد الإلكتروني للدكتور: dr.ahmed@university.ps');
        $this->command->info('كلمة المرور: password');
        $this->command->info('=====================================');
    }
}
