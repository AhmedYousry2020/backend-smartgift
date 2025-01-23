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
            $table->id(); // Primary key
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->enum('status',['active','block','freeze'])->default(('active'));
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('image')->nullable();
            $table->text('address')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('create_otp_date')->nullable();
            $table->rememberToken(); // For "remember me" functionality
            $table->timestamps(); // Created at and updated at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
