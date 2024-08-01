<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('email', 'username');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameIndex('users_email_unique', 'users_username_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('username', 'email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameIndex('users_username_unique', 'users_email_unique');
        });
    }
};
