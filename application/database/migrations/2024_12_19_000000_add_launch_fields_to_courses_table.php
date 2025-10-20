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
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('launch_type', ['regular', 'new_launch', 'upcoming'])->default('regular')->after('status');
            $table->date('launch_date')->nullable()->after('launch_type');
            $table->boolean('is_featured')->default(false)->after('launch_date');
            $table->decimal('early_bird_price', 8, 2)->nullable()->after('is_featured');
            $table->date('registration_deadline')->nullable()->after('early_bird_price');
            $table->text('launch_description')->nullable()->after('registration_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'launch_type', 
                'launch_date', 
                'is_featured', 
                'early_bird_price', 
                'registration_deadline', 
                'launch_description'
            ]);
        });
    }
};