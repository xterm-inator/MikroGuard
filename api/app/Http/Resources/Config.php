<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Config extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'peer_name' => $this->peer_name,
            'peer_private_key' => $this->peer_private_key,
            'peer_public_key' => $this->peer_public_key,
            'peer_preshared_key' => $this->peer_preshared_key,
            'server_name' => $this->server_name,
            'server_public_key' => $this->server_public_key,
            'endpoint' => $this->endpoint,
            'dns' => $this->dns,
            'allowed_ips' => $this->allowed_ips,
            'address' => $this->address,
            $this->mergeWhen($this->details, fn () =>
                [
                    'rx' => (int)$this->details['rx'],
                    'tx' => (int)$this->details['tx'],
                    'last_handshake' => generate_last_handshake_date($this->details['last-handshake'] ?? null)?->format('Y-m-d H:i:s'),
                    'last_connected_from' => $this->details['current-endpoint-address'] ?: '-',
                ]
            )
        ];
    }
}
