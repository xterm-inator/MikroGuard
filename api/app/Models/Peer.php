<?php

namespace App\Models;

use App\Events\PeerCreated;
use App\Events\PeerDeleting;
use App\Support\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peer extends Model
{
    use Uuids;

    protected $fillable = [
        'peer_name',
        'peer_private_key',
        'peer_public_key',
        'peer_preshared_key',
        'server_name',
        'server_public_key',
        'endpoint',
        'dns',
        'allowed_ips',
        'address'
    ];

    protected $casts = [
        'peer_private_key' => 'encrypted',
        'peer_public_key' => 'encrypted',
        'peer_preshared_key' => 'encrypted',
        'server_public_key' => 'encrypted'
    ];

    protected $dispatchesEvents = [
        'created' => PeerCreated::class,
        'deleting' => PeerDeleting::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
