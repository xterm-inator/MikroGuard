<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthProvider extends Model
{
    protected $table = 'oauth_providers';

    protected $guarded = ['id'];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
