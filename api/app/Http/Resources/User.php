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
            $this->mergeWhen($this->peer, fn () =>
                [
                    'rx' => (int)$this->peer['rx'],
                    'tx' => (int)$this->peer['tx'],
                    'last_handshake' => generate_last_handshake_date($this->peer['last-handshake'] ?? null)?->format('Y-m-d H:i:s'),
                ]
            ),
        ];
    }
}
