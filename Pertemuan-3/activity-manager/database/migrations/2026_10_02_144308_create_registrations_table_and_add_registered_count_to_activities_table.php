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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->string('participant_name');
            $table->string('email');
            $table->timestamp('registered_at');
            $table->timestamps();

            $table->unique(['activity_id', 'email']);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->unsignedInteger('registered_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('registered_count');
        });
    }
};
