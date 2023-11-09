<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users with their public key';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->table(
            ['Email', 'Public Key'],
            User::with('config')->get()->map(fn (User $user) =>
                [
                    'email' => $user->email,
                    'key' => $user->config?->peer_public_key
                ]
            )
        );
    }
}
