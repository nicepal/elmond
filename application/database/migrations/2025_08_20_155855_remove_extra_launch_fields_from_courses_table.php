<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Check if columns exist before dropping them
            $columns = Schema::getColumnListing('courses');
            
            $columnsToCheck = ['is_featured', 'early_bird_price', 'registration_deadline', 'launch_description'];
            $existingColumns = array_intersect($columnsToCheck, $columns);
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('launch_date');
            $table->decimal('early_bird_price', 8, 2)->nullable()->after('is_featured');
            $table->date('registration_deadline')->nullable()->after('early_bird_price');
            $table->text('launch_description')->nullable()->after('registration_deadline');
        });
    }
};
