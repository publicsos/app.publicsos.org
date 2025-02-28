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
        Schema::create('workflows', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('workspace_id')->default(1);
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workflow_id')->nullable();
            $table->bigInteger('parentable_id')->nullable()->index();
            $table->string('parentable_type')->nullable()->index();
            $table->string('type');
            $table->string('name');
            $table->json('data_fields')->nullable();
            $table->json('conditions')->nullable();
            $table->integer('node_id')->nullable();
            $table->integer('pos_x')->default(0);
            $table->integer('pos_y')->default(0);
            $table->timestamps();
        });
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workflow_log_id');
            $table->bigInteger('task_id');
            $table->string('name');
            $table->string('status');
            $table->text('message')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->timestamps();
        });
        Schema::create('triggers', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->boolean('queueable')->default(true);
            $table->json('data_fields')->nullable();
            $table->json('conditions')->nullable();
            $table->bigInteger('workflow_id')->nullable()->index();
            $table->integer('pos_x');
            $table->integer('pos_y');
            $table->timestamps();
        });
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('workflow_id')->nullable()->index();
            $table->bigInteger('elementable_id')->nullable()->index();
            $table->string('elementable_type')->nullable()->index();
            $table->bigInteger('triggerable_id')->nullable()->index();
            $table->string('triggerable_type')->nullable()->index();
            $table->string('name');
            $table->string('status');
            $table->text('message')->nullable();
            $table->text('databus')->nullable();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
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
        Schema::dropIfExists('workflows');
    }
};
