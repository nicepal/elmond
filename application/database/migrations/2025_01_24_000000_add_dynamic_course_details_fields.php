<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // About the Course fields (5 inputs)
            $table->string('duration')->nullable()->after('description');
            $table->integer('assignments_count')->default(0)->after('duration');
            $table->integer('learners_count')->default(0)->after('assignments_count');
            $table->string('access_duration')->nullable()->after('learners_count');
            $table->string('course_level')->nullable()->after('access_duration');
            
            // Course FAQ (JSON)
            $table->json('course_faqs')->nullable()->after('course_level');
            
            // Earn a Certificate fields
            $table->longText('certificate_description')->nullable()->after('course_faqs');
            $table->string('certificate_image')->nullable()->after('certificate_description');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'duration', 'assignments_count', 'learners_count', 'access_duration', 
                'course_level', 'course_faqs', 'certificate_description', 'certificate_image'
            ]);
        });
    }
};