<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['course_level', 'learners_count']);
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('course_level')->nullable();
            $table->integer('learners_count')->default(0);
        });
    }
};
