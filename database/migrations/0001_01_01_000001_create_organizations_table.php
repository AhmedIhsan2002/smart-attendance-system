<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->string('subdomain')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // العلاقات
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
           $table->foreignId('owner_id')->nullable(); // بدون foreign key

            // الاشتراك
            $table->enum('subscription_status', ['trial', 'active', 'expired', 'cancelled', 'suspended'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();

            // الحدود
            $table->integer('max_students')->default(100);
            $table->integer('max_instructors')->default(10);
            $table->integer('max_courses')->default(20);

            // API
            $table->string('api_key')->unique()->nullable();

            // إعدادات إضافية
            $table->json('settings')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
