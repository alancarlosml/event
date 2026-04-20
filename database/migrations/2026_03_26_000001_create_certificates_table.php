<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 200)->unique();
            $table->string('code', 50)->unique();
            $table->integer('event_id')->index()->unsigned();
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
            $table->integer('participante_id')->index()->unsigned();
            $table->foreign('participante_id')
                ->references('id')
                ->on('participantes')
                ->onDelete('cascade');
            $table->integer('order_id')->index()->unsigned();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'participante_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
