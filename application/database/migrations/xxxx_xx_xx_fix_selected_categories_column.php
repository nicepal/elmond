<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSelectedCategoriesColumn extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'selected_categories')) {
                $table->json('selected_categories')->nullable()->after('category_id');
            }
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'selected_categories')) {
                $table->dropColumn('selected_categories');
            }
        });
    }
}