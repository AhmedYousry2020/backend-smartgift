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
        // Products table
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade'); // Foreign key to companies
            $table->integer('bottle_count'); // Number of bottles in the package
            $table->decimal('price', 10, 2); // Price of the package
            $table->timestamps(); // Created at and updated at
        });

        // Translations for products
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('locale')->index(); // Language code (e.g., 'en', 'ar')
            $table->string('name'); // Translated name
            $table->unique(['product_id', 'locale']);
            $table->text('description')->nullable(); // Translated description
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_translations');

    }
};
