<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('counties', function (Blueprint $table) {
            $table->id();
            $table->string('county', 80)->nullable();
            $table->timestamps();
        });

        Schema::create('places_multicode', function (Blueprint $table) {
            $table->id();
            $table->string('county', 80)->nullable();
            $table->string('place', 80)->nullable();
            $table->timestamps();
        });


        Schema::create('domains', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();
            $table->string('iso_code', 2);
            $table->string('country', 50);
            $table->string('county', 80)->nullable();
            $table->string('place', 80)->nullable();
            $table->boolean('place_multicode')->default(false);
            $table->string('district', 80)->nullable();
            $table->string('street_suffix', 80)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('street_number', 45)->default('');
            $table->string('postal_code', 15)->nullable();
            $table->timestamps();
        });

        ///si aici vine logica pentru telefoane
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
        Schema::dropIfExists('counties');
        Schema::dropIfExists('places_multicode');
        Schema::dropIfExists('postal_codes');
    }
};
