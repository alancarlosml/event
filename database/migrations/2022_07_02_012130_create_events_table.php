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

        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash')->unique();
            $table->string('name')->unique();
            $table->string('subtitle');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('banner');
            $table->integer('banner_option');
            $table->string('max_tickets');
            $table->string('theme');
            $table->string('contact');
            $table->integer('status');
            // $table->foreignId('owner_id')->nullable()
            //     ->constrained('owners')
            //     ->onDelete('set null');

            $table->integer('owner_id')->index()->nullable()->unsigned();
            $table->foreign('owner_id')
                ->references('id')
                ->on('owners')
                ->onDelete('cascade');
            $table->integer('place_id')->index()->nullable()->unsigned();
            $table->foreign('place_id')
                ->references('id')
                ->on('places')
                ->onDelete('cascade');
            $table->integer('area_id')->index()->nullable()->unsigned();
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
