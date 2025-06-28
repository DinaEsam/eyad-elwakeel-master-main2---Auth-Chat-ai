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
        Schema::create('medication_reminders', function (Blueprint $table) {
        $table->id();
        $table->string('medicine_name');
        $table->integer('dose_count');
        $table->string('period');
        $table->time('time');
        $table->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_reminders');
    }
};
