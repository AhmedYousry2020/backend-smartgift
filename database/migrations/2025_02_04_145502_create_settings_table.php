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
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); // Primary key            
            $table->timestamps(); // Created at and updated at
        });

        Schema::create('setting_translations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('setting_id');
            $table->string('locale')->index();; // Language code (e.g., 'en', 'ar')
            $table->text('terms_and_conditions'); // For Terms and Conditions
            $table->text('privacy_policy'); // For Privacy Policy
            $table->unique(['setting_id', 'locale']);
            $table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
