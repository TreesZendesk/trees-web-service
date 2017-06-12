<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_mst', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('employee_number', 30);
            $table->string('employee_name', 100);
            $table->string('cell_no', 15);
            $table->datetime('creation_date');

            $table->primary('employee_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emp_mst');

    }
}
