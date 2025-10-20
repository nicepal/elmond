<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('admin_status');
            $table->decimal('early_bird_price', 28, 8)->nullable()->after('price');
            $table->date('registration_deadline')->nullable()->after('early_bird_price');
            $table->text('launch_description')->nullable()->after('registration_deadline');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'early_bird_price', 'registration_deadline', 'launch_description']);
        });
    }
};