<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('trx_detail_id');
            $table->integer('trx_header_id')->unsigned();
            $table->string('taxi_from', 50);
            $table->string('taxi_to', 50);
            $table->time('taxi_time');
            $table->string('taxi_voucher_no', 50);
            $table->integer('taxi_amount');
            $table->integer('batch_id')->nullable();
        });

        Schema::table('claim_detail', function (Blueprint $table) {
            $table->foreign('trx_header_id')->references('trx_id')->on('claim_header');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_detail');

    }
}
