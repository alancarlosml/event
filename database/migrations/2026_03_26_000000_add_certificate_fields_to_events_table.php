<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->tinyInteger('certificate_enabled')->default(0)->after('mercadopago_link');
            $table->string('certificate_hours', 50)->nullable()->after('certificate_enabled');
            $table->string('certificate_logo')->nullable()->after('certificate_hours');
            $table->string('certificate_signature_image')->nullable()->after('certificate_logo');
            $table->string('certificate_signature_name')->nullable()->after('certificate_signature_image');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_enabled',
                'certificate_hours',
                'certificate_logo',
                'certificate_signature_image',
                'certificate_signature_name',
            ]);
        });
    }
};
