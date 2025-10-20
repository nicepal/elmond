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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->boolean('whatsapp_enabled')->default(0)->after('agree');
            $table->string('whatsapp_number')->nullable()->after('whatsapp_enabled');
            $table->text('whatsapp_message')->nullable()->after('whatsapp_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_enabled', 'whatsapp_number', 'whatsapp_message']);
        });
    }
};