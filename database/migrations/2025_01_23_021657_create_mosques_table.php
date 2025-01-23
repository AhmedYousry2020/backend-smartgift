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
        Schema::create('mosques', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->decimal('lat', 10, 7); // Latitude with precision
            $table->decimal('lng', 10, 7); // Longitude with precision
            $table->text('address')->nullable(); // Optional address field
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->boolean('is_high_need')->default(false); // Flag for "المساجد الاكثر احتياجا"
            $table->timestamps(); // Created at and updated at
        });

        Schema::create('mosque_translations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('mosque_id');
            $table->string('locale')->index();; // Language code (e.g., 'en', 'ar')
            $table->string('name'); // Translated name
            $table->unique(['mosque_id', 'locale']);
            $table->foreign('mosque_id')->references('id')->on('mosques')->onDelete('cascade');
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mosques');
        Schema::dropIfExists('mosque_translations');

    }
};
