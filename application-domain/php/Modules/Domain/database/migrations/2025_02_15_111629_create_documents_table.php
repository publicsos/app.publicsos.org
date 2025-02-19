<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('source')->nullable();
            $table->string('file')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
