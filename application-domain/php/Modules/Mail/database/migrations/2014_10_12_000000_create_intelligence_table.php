<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**

 */
return new class extends  Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('intelligence', function (Blueprint $table) {
            $table->id();
            $table->string('scantarget'); // Target of the scan
            $table->integer('workspace_id')->unsigned()->nullable();
            //       this will map the scanid used to track the scan progress
            $table->string('scan_id')->index()->nullable();
            $table->string('status')->default('draft');
            $table->json('results')->nullable();

            $table->timestamps(); // Created at and Updated at timestamps
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intelligence');
    }
};
