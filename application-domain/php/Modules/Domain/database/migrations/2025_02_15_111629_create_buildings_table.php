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
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->string('title')->nullable();
            $table->string('blocuri_id')->nullable();
            $table->text('description')->nullable();
            $table->string('properties_face')->nullable();
            $table->string('properties_icon_url')->nullable();
            $table->unsignedInteger('properties_icon_scaledSize_width')->nullable();
            $table->unsignedInteger('properties_icon_scaledSize_height')->nullable();
            $table->unsignedInteger('properties_icon_origin_x')->nullable();
            $table->unsignedInteger('properties_icon_origin_y')->nullable();
            $table->unsignedInteger('properties_icon_anchor_x')->nullable();
            $table->unsignedInteger('properties_icon_anchor_y')->nullable();
            $table->decimal('distance_miles', 8, 2)->nullable();


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buildings');
    }
};
