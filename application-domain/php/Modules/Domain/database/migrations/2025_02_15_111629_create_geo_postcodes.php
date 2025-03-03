<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('postcodes_geolocations', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 10);
            $table->string('zipcode')->nullable();
            $table->string('place')->nullable();
            $table->string('state')->nullable();
            $table->string('state_code', 10)->nullable();
            $table->string('province')->nullable();
            $table->string('province_code', 10)->nullable();
            $table->string('community')->nullable();
            $table->string('community_code', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('postcodes_geolocations');
    }
};
