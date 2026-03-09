<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('gatway_hash', 'idx_orders_gatway_hash');
            $table->index(['participante_id', 'status'], 'idx_orders_participante_status');
            $table->index(['event_id', 'status'], 'idx_orders_event_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('purchase_hash', 'idx_order_items_purchase_hash');
            $table->index('status', 'idx_order_items_status');
            $table->index('lote_id', 'idx_order_items_lote_id');
        });

        Schema::table('participantes_events', function (Blueprint $table) {
            $table->index(['event_id', 'role', 'status'], 'idx_participantes_events_event_role_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_gatway_hash');
            $table->dropIndex('idx_orders_participante_status');
            $table->dropIndex('idx_orders_event_status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('idx_order_items_purchase_hash');
            $table->dropIndex('idx_order_items_status');
            $table->dropIndex('idx_order_items_lote_id');
        });

        Schema::table('participantes_events', function (Blueprint $table) {
            $table->dropIndex('idx_participantes_events_event_role_status');
        });
    }
};
