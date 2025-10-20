<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organization_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('assigned_price', 28, 8)->nullable(); // Custom price for organization
            $table->date('assigned_at');
            $table->date('expires_at')->nullable(); // Course access expiry
            $table->integer('max_enrollments')->nullable(); // Max employees who can enroll
            $table->tinyInteger('status')->default(1); // 1=active, 0=inactive
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate course assignments
            $table->unique(['organization_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_courses');
    }
};
