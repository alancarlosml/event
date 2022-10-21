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
        Schema::create('option_answers', function (Blueprint $table) {
            $table->id();
            $table->string('answer');
            $table->integer('question_id')->unsigned()->index();
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');
            $table->integer('participante_lote_id')->unsigned()->index();
            $table->foreign('participante_lote_id')
                ->references('id')
                ->on('participantes_lotes')
                ->onDelete('cascade');
            $table->integer('order_id')->unsigned()->index();
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
        Schema::dropIfExists('option_answers');
    }
};
