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
        
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash')->unique();
            $table->string('code')->unique();
            $table->integer('discount_type');
            $table->double('discount_value');
            $table->integer('limit_buy');
            $table->integer('limit_tickets');
            $table->integer('status');
            $table->integer('event_id')->index()->unsigned();
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
        Schema::dropIfExists('coupons');
    }
};
