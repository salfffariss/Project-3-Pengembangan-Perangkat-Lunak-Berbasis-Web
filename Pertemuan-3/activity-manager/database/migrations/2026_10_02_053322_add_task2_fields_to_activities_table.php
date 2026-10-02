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
        Schema::table('activities', function (Blueprint $table) {
            $table->date('start_at')->nullable()->after('description');
            $table->date('end_at')->nullable()->after('start_at');
            $table->string('location', 100)->nullable()->after('end_at');
            $table->unsignedInteger('capacity')->default(100)->after('location');
            $table->dropColumn('activity_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->date('activity_date')->nullable();
            $table->dropColumn(['start_at', 'end_at', 'location', 'capacity']);
        });
    }
};
