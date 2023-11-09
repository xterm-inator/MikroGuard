<?php

namespace App\Support\Enums;

enum Auth: string
{
    case Form = 'form';
    case Google = 'google';

    public function title(): string
    {
        return match ($this) {
            self::Form => 'Form',
            self::Google => 'Google'
        };
    }
}
