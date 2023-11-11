<?php

namespace App\Models;

use App\Support\Enums\Role;
use App\Support\Traits\Uuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'role' => Role::class,
    ];

    public function password(): Attribute
    {
        return new Attribute(set: fn ($value) => $value ? bcrypt($value) : null);
    }

    public function oauthProviders(): HasMany
    {
        return $this->hasMany(OAuthProvider::class);
    }

    public function config(): HasOne
    {
        return $this->hasOne(Config::class);
    }
}
