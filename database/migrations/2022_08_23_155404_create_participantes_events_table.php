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
        Schema::create('participantes_events', function (Blueprint $table) {
            $table->bigIncrements('id');        
            $table->string('hash')->unique(); 
            $table->string('role');        
            $table->integer('status');        
            $table->timestamp('created_at');        
            $table->integer('participante_id');
            $table->foreign('participante_id')
                ->references('id')
                ->on('participantes')->onDelete('cascade');
            $table->integer('event_id');
            $table->foreign('event_id')
                ->references('id')
                ->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participantes_events');
    }
};
