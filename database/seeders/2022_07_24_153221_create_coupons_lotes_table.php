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
        Schema::create('coupons_lotes', function (Blueprint $table) {
            $table->bigIncrements('id');        
            $table->integer('coupon_id');
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')->onDelete('cascade');
            $table->integer('lote_id');
            $table->foreign('lote_id')
                ->references('id')
                ->on('lotes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons_lotes');
    }
};
