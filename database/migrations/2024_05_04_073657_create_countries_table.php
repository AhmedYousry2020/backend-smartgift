<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('flag')->nullable();
            $table->timestamps();
        });

        Schema::create('country_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('country_id');
            $table->string('locale')->index();
            $table->text('name');
            $table->unique(['country_id','locale']);
            $table->foreign('country_id')->references('id')->on('countries')->delete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_translations');
        Schema::dropIfExists('countries');
    }
}
