<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('longitude', 32)->nullable();
            $table->string('latitude', 32)->nullable();
            $table->string('title')->nullable();
            $table->string('remote_id')->nullable();
            $table->string('address')->nullable();
            $table->string('risk')->nullable();
            $table->string('apartments')->nullable();
            $table->string('age_group')->nullable();
            $table->string('height')->nullable();
            $table->string('postcode')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buildings');
    }
};
