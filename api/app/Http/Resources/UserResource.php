<?php

namespace App\Http\Resources;

use App\Support\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'role' => $this->when($request->user()->role === Role::Admin, $this->role),
            $this->mergeWhen($this->router_peers, fn () =>
                [
                    'rx' => (int)$this->router_peers->sum('rx'),
                    'tx' => (int)$this->router_peers->sum('rx'),
                    'last_handshake' => $this->router_peers
                        ->filter(fn ($peer) => $peer['last-handshake'] ?? false)
                        ->max(fn ($peer) => generate_last_handshake_date($peer['last-handshake']))
                        ?->format('Y-m-d H:i:s'),
                ]
            ),
        ];
    }
}
