<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boleto_details', function (Blueprint $table) {
            $table->string('href')->nullable()->change();
            $table->string('line_code')->nullable()->change();
            $table->string('href_print')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('boleto_details', function (Blueprint $table) {
            $table->string('href')->nullable(false)->change();
            $table->string('line_code')->nullable(false)->change();
            $table->string('href_print')->nullable(false)->change();
        });
    }
};
