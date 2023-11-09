<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Enums\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user {email} {role=user : Role of the user (user|admin)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ValidationException
     */
    public function handle(): int
    {
        $validator = Validator::make($this->arguments(), [
            'email' => ['email'],
            'role' => [new Enum(Role::class)]
        ]);

        if ($validator->errors()->count()) {
            foreach ($validator->errors()->messages() as $errors) {
                foreach ($errors as $error) {
                    $this->error($error);
                }
            }
            return 0;
        }

        User::create($validator->validated());

        $this->info('User created');

        return 0;
    }
}
