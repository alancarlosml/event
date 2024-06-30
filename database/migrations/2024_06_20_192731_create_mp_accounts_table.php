<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('participante_id');
            $table->text('access_token');
            $table->text('public_key');
            $table->text('refresh_token');
            $table->integer('expires_in');
            $table->string('mp_user_id', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_accounts');
    }
}
