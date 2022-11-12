<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('peer_name');
            $table->string('peer_private_key');
            $table->string('peer_public_key');
            $table->string('peer_preshared_key');
            $table->string('server_name');
            $table->string('server_public_key');
            $table->string('endpoint');
            $table->string('dns');
            $table->string('allowed_ips');
            $table->string('address');
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
        Schema::dropIfExists('user_config');
    }
};
