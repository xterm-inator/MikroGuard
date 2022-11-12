<?php

namespace App\Http\Resources;

use App\Support\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'email' => $this->email,
            'role' => $this->when($request->user()->role === Role::Admin, $this->role),
        ];
    }
}
