<?php

namespace App\Listeners;

use App\Events\ConfigDeleting;
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
     * @param  \App\Events\ConfigDeleting  $event
     * @return void
     */
    public function handle(ConfigDeleting $event)
    {
        WireGuard::deletePeer($event->config->peer_public_key);
    }
}
