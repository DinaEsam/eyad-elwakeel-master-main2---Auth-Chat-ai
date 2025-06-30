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
        Schema::create('doctors', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // كل دكتور مرتبط بمستخدم
            $table->string('specialty')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('waiting_time')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
