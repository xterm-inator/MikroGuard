<?php

namespace App\Listeners;

use App\Events\PeerCreated;
use App\RouterOS\Data\Peer;
use App\RouterOS\WireGuard;

class CreateWireGuardPeer
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PeerCreated $event
     * @return void
     */
    public function handle(PeerCreated $event): void
    {
        $peer = new Peer(
            $event->config->peer_name,
            $event->config->address,
            $event->config->peer_public_key,
            config('services.wireguard.interface'),
            $event->config->peer_preshared_key
        );

        WireGuard::createPeer($peer);
    }
}
