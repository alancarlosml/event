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
        Schema::create('pix_details', function (Blueprint $table) {
            $table->increments('id');
            $table->double('value');
            $table->string('qr_code');
            $table->text('qr_code_base64');
            $table->string('ticket_url');
            $table->timestamp('expiration_date');
            $table->integer('order_id')->index()->unsigned();
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
        Schema::dropIfExists('pix_details');
    }
};
