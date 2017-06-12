<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsenceTrxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absence_trx', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('abs_trx_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('project_number', 20)->nullable();
            $table->string('employee_number', 30);
            $table->string('activity_status', 255);
            $table->string('created_by', 30);
            $table->datetime('creation_date', 30);
            $table->integer('batch_id')->nullable();
        });

        Schema::table('absence_trx', function(Blueprint $table) {
            $table->foreign('employee_number')->references('employee_number')->on('emp_mst');
            $table->foreign('project_number')->references('project_number')->on('project_mst');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absence_trx');

    }
}
