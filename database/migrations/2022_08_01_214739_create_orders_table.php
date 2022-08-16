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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();  
            $table->integer('quantity');  
            $table->timestamp('date_used');  
            $table->string('gatway_hash');  
            $table->string('gatway_reference');  
            $table->string('gatway_status');  
            $table->string('gatway_payment_method');  
            $table->integer('participante_lote_id')->unsigned()->index();
            $table->foreign('participante_lote_id')
                ->references('id')
                ->on('participantes_lotes')
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
        Schema::dropIfExists('orders');
    }
};
