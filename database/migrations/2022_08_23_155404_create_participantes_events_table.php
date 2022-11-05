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
        
        Schema::create('participantes_events', function (Blueprint $table) {
            $table->increments('id');       
            $table->string('hash')->unique(); 
            $table->string('role');        
            $table->integer('status');        
            $table->timestamp('created_at');        
            $table->integer('participante_id')->index()->unsigned();
            $table->foreign('participante_id')
                ->references('id')
                ->on('participantes')->onDelete('cascade');
            $table->integer('event_id')->index()->unsigned();
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
