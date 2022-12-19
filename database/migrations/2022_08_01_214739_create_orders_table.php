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
        
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash')->unique();  
            $table->integer('status');  
            $table->timestamp('date_used');  
            $table->string('gatway_hash');  
            $table->string('gatway_reference');  
            $table->string('gatway_status');  
            $table->string('gatway_payment_method');  
            $table->integer('event_date_id')->index()->unsigned();
            $table->foreign('event_date_id')
                ->references('id')
                ->on('event_dates')
                ->onDelete('cascade');
            $table->integer('participante_id')->index()->unsigned();
            $table->foreign('participante_id')
                ->references('id')
                ->on('participantes')
                ->onDelete('cascade');
            $table->integer('coupon_id')->index()->unsigned();
                $table->foreign('coupon_id')
                    ->references('id')
                    ->on('coupons')->onDelete('cascade');
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
