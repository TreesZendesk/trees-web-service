<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_header', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('trx_id');
            $table->date('claim_date');
            $table->string('activity_code', 20);
            $table->string('client_code', 100)->nullable();
            $table->string('employee_number', 30);
            $table->integer('toll_from')->nullable();
            $table->integer('toll_to')->nullable();
            $table->integer('mileage')->nullable();
            $table->integer('parking')->nullable();
            $table->integer('meal')->nullable();
            $table->string('created_by', 30);
            $table->datetime('creation_date');
            $table->integer('batch_id')->nullable();
        });

        Schema::table('claim_header', function (Blueprint $table) {
            $table->foreign('employee_number')->references('employee_number')->on('emp_mst');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_header');

    }
}
