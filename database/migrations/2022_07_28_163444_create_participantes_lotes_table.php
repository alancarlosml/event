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
        Schema::create('participantes_lotes', function (Blueprint $table) {
            $table->increments('id');       
            $table->string('hash')->unique(); 
            $table->integer('number')->unique();        
            $table->timestamp('created_at');        
            $table->integer('status');        
            $table->integer('participante_id')->index();
            $table->foreign('participante_id')
                ->references('id')
                ->on('participantes')->onDelete('cascade');
            $table->integer('lote_id')->index();
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
        Schema::dropIfExists('participantes_lotes');
    }
};
