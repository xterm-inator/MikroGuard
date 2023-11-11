<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Enums\Auth;
use App\Support\Enums\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;
use function Laravel\Prompts\password;

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
        $password = null;

        if (auth_type() == Auth::Form) {
            $password = password('User password', required: true);
        }

        $validator = Validator::make([...$this->arguments(), 'password' => $password], [
            'email' => ['email', new Unique('users', 'email')],
            'role' => [new Enum(Role::class)],
            'password' => ['nullable', new RequiredIf(auth_type() == Auth::Form), 'min:8'],
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
