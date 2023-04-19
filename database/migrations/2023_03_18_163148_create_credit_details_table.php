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
        Schema::create('credit_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->integer('installments');
            $table->double('value');
            $table->double('installment_amount');
            $table->double('total_paid_amount');
            $table->double('net_received_amount');
            $table->double('total_amount_tax');
            $table->string('payment_method_id');
            $table->integer('order_id')->index()->unsigned();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
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
        Schema::dropIfExists('credit_details');
    }
};
