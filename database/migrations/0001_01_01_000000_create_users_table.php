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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['patient', 'doctor', 'admin'])->default('patient'); // تحديد الدور
            $table->string('national_id')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->boolean('has_chronic_diseases')->default(false);
            $table->boolean('is_following_with_doctor')->default(false);
            $table->date('diagnosis_date')->nullable();
            $table->string('disease_stage')->nullable();
            // $table->decimal('latitude', 10, 7)->nullable();
            // $table->decimal('longitude', 10, 7)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
