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
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('users', 'firstname')) {
                $table->string('firstname')->nullable();
            }
            if (!Schema::hasColumn('users', 'lastname')) {
                $table->string('lastname')->nullable();
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->nullable();
            }
            
            // User status and verification
            if (!Schema::hasColumn('users', 'status')) {
                $table->tinyInteger('status')->default(1); // 1=active, 0=banned
            }
            if (!Schema::hasColumn('users', 'ev')) {
                $table->tinyInteger('ev')->default(0); // email verification
            }
            if (!Schema::hasColumn('users', 'sv')) {
                $table->tinyInteger('sv')->default(0); // SMS verification
            }
            if (!Schema::hasColumn('users', 'kv')) {
                $table->tinyInteger('kv')->default(0); // KYC verification
            }
            
            // Financial
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 28, 8)->default(0);
            }
            
            // Additional data
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'kyc_data')) {
                $table->text('kyc_data')->nullable();
            }
            if (!Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile')->nullable();
            }
            if (!Schema::hasColumn('users', 'ref_by')) {
                $table->string('ref_by')->nullable();
            }
            
            // Verification codes
            if (!Schema::hasColumn('users', 'ver_code')) {
                $table->string('ver_code')->nullable();
            }
            if (!Schema::hasColumn('users', 'ver_code_send_at')) {
                $table->dateTime('ver_code_send_at')->nullable();
            }
            
            // Timestamps for bans
            if (!Schema::hasColumn('users', 'ban_reason')) {
                $table->dateTime('ban_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'firstname', 'lastname', 'username', 'status', 'ev', 'sv', 'kv',
                'balance', 'address', 'kyc_data', 'country_code', 'mobile', 
                'ref_by', 'ver_code', 'ver_code_send_at', 'ban_reason'
            ]);
        });
    }
};