<?php

namespace App\Providers;

use App\Events\PeerCreated;
use App\Events\PeerDeleting;
use App\Listeners\CreateWireGuardPeer;
use App\Listeners\RemoveWireGuardPeer;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PeerCreated::class => [
            CreateWireGuardPeer::class,
        ],
        PeerDeleting::class => [
            RemoveWireGuardPeer::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
