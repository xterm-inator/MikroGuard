<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('BEGIN EXCLUSIVE TRANSACTION');
        Schema::create('peers', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
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

        DB::table('user_config')->cursor()->each(fn (StdClass $config) =>
            DB::table('peers')->insert([...get_object_vars($config), 'uuid' => Str::uuid()->toString()])
        );

        Schema::drop('user_config');

        DB::statement('COMMIT TRANSACTION');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('BEGIN EXCLUSIVE TRANSACTION');
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

        DB::table('peers')->cursor()->each(fn (StdClass $config) =>
            DB::table('user_config')->insert(array_diff_key(get_object_vars($config), array_flip(['uuid'])))
        );

        Schema::drop('peers');

        DB::statement('COMMIT TRANSACTION');
    }
};
