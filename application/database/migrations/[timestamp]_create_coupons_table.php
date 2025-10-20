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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('minimum_purchase', 10, 2)->default(0);
            $table->integer('usage_limit')->nullable(); // Total number of times this coupon can be used
            $table->integer('usage_limit_per_user')->nullable(); // How many times a single user can use this coupon
            $table->boolean('is_first_purchase_only')->default(false); // Only for first-time buyers
            $table->boolean('is_registration_bonus')->default(false); // Given upon registration
            $table->json('applicable_courses')->nullable(); // Specific courses this coupon applies to
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};