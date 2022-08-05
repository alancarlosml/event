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
        Schema::create('inscricoes_coupons', function (Blueprint $table) {
            $table->bigIncrements('id');        
            $table->integer('participante_lote_id');
            $table->foreign('participante_lote_id')
                ->references('id')
                ->on('participantes_lotes')->onDelete('cascade');
            $table->integer('coupon_id');
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
        Schema::dropIfExists('inscricoes_coupons');
    }
};
