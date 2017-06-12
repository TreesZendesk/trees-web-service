<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_mst', function (Blueprint $table) {
            $table->string('customer_code', 5);
            $table->string('customer_name', 100);
            $table->string('city', 50)->nullable();
            $table->string('street', 200)->nullable();
            $table->integer('standard_km_from');
            $table->integer('standard_toll')->nullable();
            $table->integer('standard_km_to');
            $table->datetime('creation_date');

            $table->primary('customer_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_mst');

    }
}
