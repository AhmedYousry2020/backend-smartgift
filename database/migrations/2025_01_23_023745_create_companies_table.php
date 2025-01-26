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
          // Companies table
          Schema::create('companies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->timestamps(); // Created at and updated at
        });

         // Translations for companies
         Schema::create('company_translations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('locale')->index(); // Language code (e.g., 'en', 'ar')
            $table->unique(['company_id', 'locale']);
            $table->string('name'); // Translated name
            $table->timestamps(); // Created at and updated at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
        Schema::dropIfExists('company_translations');

    }
};
