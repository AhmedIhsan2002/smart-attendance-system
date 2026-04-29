<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar');
            $table->string('slug')->unique();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->integer('max_students')->default(100);
            $table->integer('max_instructors')->default(10);
            $table->integer('max_courses')->default(20);
            $table->integer('max_departments')->default(5);
            $table->json('features')->nullable();
            $table->boolean('has_face_recognition')->default(false);
            $table->boolean('has_api_access')->default(false);
            $table->boolean('has_advanced_reports')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
