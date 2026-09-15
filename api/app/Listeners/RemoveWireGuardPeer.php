<?php

namespace App\Listeners;

use App\Events\PeerDeleting;
use App\RouterOS\WireGuard;

class RemoveWireGuardPeer
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
     * @param  \App\Events\PeerDeleting  $event
     * @return void
     */
    public function handle(PeerDeleting $event)
    {
        WireGuard::deletePeer($event->config->peer_public_key);
    }
}
