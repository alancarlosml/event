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
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'checkin_status')) {
                $table->tinyInteger('checkin_status')->default(0)->after('purchase_hash')->comment('0 = não fez check-in, 1 = fez check-in');
            }
            if (!Schema::hasColumn('order_items', 'checkin_at')) {
                $table->timestamp('checkin_at')->nullable()->after('checkin_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'checkin_status')) {
                $table->dropColumn('checkin_status');
            }
            if (Schema::hasColumn('order_items', 'checkin_at')) {
                $table->dropColumn('checkin_at');
            }
        });
    }
};

