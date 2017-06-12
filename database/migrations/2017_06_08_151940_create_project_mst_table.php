<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_mst', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('project_number', 20);
            $table->string('project_name', 100);
            $table->integer('standard_km_from');
            $table->integer('standard_toll')->nullable();
            $table->integer('standard_km_to');
            $table->datetime('creation_date');

            $table->primary('project_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_mst');
    }
}
