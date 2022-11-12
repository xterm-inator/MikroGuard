<?php

namespace App\Models;

use App\Events\ConfigCreated;
use App\Events\ConfigDeleting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Config extends Model
{
    protected $table = 'user_config';

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
        'created' => ConfigCreated::class,
        'deleting' => ConfigDeleting::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
