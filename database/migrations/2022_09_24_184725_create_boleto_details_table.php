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
        Schema::create('boleto_details', function (Blueprint $table) {
            $table->id();
            $table->double('value');
            $table->string('href');
            $table->string('line_code');
            $table->string('href_print');
            $table->timestamp('expiration_date');
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
        Schema::dropIfExists('boleto_details');
    }
};
