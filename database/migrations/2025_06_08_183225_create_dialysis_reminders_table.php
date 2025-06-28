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
      Schema::create('dialysis_reminders', function (Blueprint $table) {
        $table->id();
        $table->integer('sessions_per_week');
        $table->date('start_date');
        $table->string('session_time');
        $table->timestamps();
        
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialysis_reminders');
    }
};
