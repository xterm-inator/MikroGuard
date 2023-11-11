<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Support\Enums\Auth;
use App\Support\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\RequiredIf;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('store', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => [
                'bail',
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'role' => [
                'bail',
                'required',
                new Enum(Role::class),
            ],
            'password' => [
                'nullable',
                new RequiredIf(auth_type() == Auth::Form),
                'confirmed'
            ]
        ];
    }
}
