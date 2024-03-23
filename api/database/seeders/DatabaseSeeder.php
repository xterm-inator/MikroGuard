<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Enums\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
         User::factory()->create([
             'email' => 'admin@xterm.me',
             'password' => 'secret',
             'role' => Role::Admin,
         ]);

         User::factory()->create([
             'email' => 'user@xterm.me',
             'password' => 'secret',
             'role' => Role::User,
         ]);
    }
}
