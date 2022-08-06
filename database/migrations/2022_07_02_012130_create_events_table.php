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
        Schema::create('events', function (Blueprint $table) {
            $table->id();        
            $table->string('hash')->unique();    
            $table->string('name')->unique();
            $table->string('subtitle');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('banner');
            $table->string('max_tickets');
            $table->integer('status');
            $table->integer('owner_id')->unsigned()->index();
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('owners')
                  ->onDelete('cascade');
            $table->integer('place_id')->unsigned()->index();
            $table->foreign('place_id')
            ->references('id')
            ->on('places')
            ->onDelete('cascade');
            $table->integer('area_id')->unsigned()->index();
            $table->foreign('area_id')
                  ->references('id')
                  ->on('areas')
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
        Schema::dropIfExists('events');
    }
};
