<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->integer('order_item_id')->nullable()->index()->unsigned()->after('order_id');
            $table->foreign('order_item_id')
                ->references('id')
                ->on('order_items')
                ->onDelete('cascade');
            $table->dropUnique(['event_id', 'participante_id']);
            $table->unique(['event_id', 'order_item_id'], 'event_order_item_unique');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['order_item_id']);
            $table->dropColumn('order_item_id');
            $table->unique(['event_id', 'participante_id']);
        });
    }
};
