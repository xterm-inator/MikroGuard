<?php

namespace App\Support\Enums;

enum Role: string
{
    case Admin = 'admin';
    case User = 'user';

    public function title(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::User => 'User'
        };
    }
}
