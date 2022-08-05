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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->double('value');
            $table->double('tax');
            $table->double('final_value');
            $table->integer('type');
            $table->integer('quantity');
            $table->integer('visibility');
            $table->integer('tax_parcelamento');
            $table->integer('tax_service');
            $table->integer('limit_min');
            $table->integer('limit_max');
            $table->datetime('datetime_begin');
            $table->datetime('datetime_end');
            $table->integer('order');
            $table->integer('event_id')->unsigned()->index();;
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
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
        Schema::dropIfExists('lotes');
    }
};
