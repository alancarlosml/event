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
            $table->integer('quantity');
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
            $table->integer('participante_lote_id')->index()->unsigned();
            $table->foreign('participante_lote_id')
                ->references('id')
                ->on('participantes_lotes')
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
        Schema::dropIfExists('orders');
    }
};
