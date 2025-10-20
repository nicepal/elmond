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
        Schema::table('coupons', function (Blueprint $table) {
            $table->text('description')->nullable()->after('code');
            $table->boolean('is_first_purchase_only')->default(false)->after('usage_limit_per_user');
            $table->boolean('is_registration_bonus')->default(false)->after('is_first_purchase_only');
            $table->json('applicable_courses')->nullable()->after('is_registration_bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_first_purchase_only', 'is_registration_bonus', 'applicable_courses']);
        });
    }
};