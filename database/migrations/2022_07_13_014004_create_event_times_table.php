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

        Schema::create('event_times', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('time')->useCurrent()->useCurrentOnUpdate();
            $table->integer('status');
            $table->integer('event_dates_id')->index()->nullable()->unsigned();
            $table->foreign('event_dates_id')
                ->references('id')
                ->on('event_dates')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_times');
    }
};

