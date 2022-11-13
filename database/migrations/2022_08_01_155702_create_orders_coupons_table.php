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
        Schema::disableForeignKeyConstraints();

        Schema::create('orders_coupons', function (Blueprint $table) {
            $table->increments('id');      
            $table->integer('order_id')->index()->unsigned();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')->onDelete('cascade');
            $table->integer('coupon_id')->index()->unsigned();
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_coupons');
    }
};
