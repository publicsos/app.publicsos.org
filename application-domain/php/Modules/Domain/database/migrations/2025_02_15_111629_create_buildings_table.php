<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {

        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('source')->nullable();
            $table->string('file')->nullable();
            $table->string('date')->nullable();
            $table->string('title')->nullable();
            $table->string('address')->nullable();
            $table->string('type')->nullable();
            $table->string('geometry_type')->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->unsignedBigInteger('properties_id')->nullable();
            $table->string('properties_title')->nullable();
            $table->text('properties_description')->nullable();
            $table->string('properties_face')->nullable();
            $table->unsignedInteger('properties_icon_scaledSize_width')->nullable();
            $table->unsignedInteger('properties_icon_scaledSize_height')->nullable();
            $table->unsignedInteger('properties_icon_origin_x')->nullable();
            $table->unsignedInteger('properties_icon_origin_y')->nullable();
            $table->unsignedInteger('properties_icon_anchor_x')->nullable();
            $table->unsignedInteger('properties_icon_anchor_y')->nullable();
            $table->decimal('distance_miles', 8, 2)->nullable();
            $table->string('contributor')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });


    }

    public function down()
    {
        Schema::dropIfExists('buildings');
    }
};
