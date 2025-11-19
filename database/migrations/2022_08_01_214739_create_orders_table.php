<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
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
            $table->integer('status');  // 1 - confirmado / 2 - pendente / 3 - cancelado
            $table->timestamp('date_used')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->string('gatway_hash')->nullable();
            $table->string('gatway_reference')->nullable();
            $table->string('gatway_status')->nullable();
            $table->string('gatway_payment_method')->nullable();
            $table->string('gatway_description')->nullable();
            $table->timestamp('gatway_date_status')->nullable();
            $table->integer('event_id')->index()->unsigned();
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
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
            $table->integer('coupon_id')->index()->nullable()->unsigned();
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')->onDelete('set null');
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
