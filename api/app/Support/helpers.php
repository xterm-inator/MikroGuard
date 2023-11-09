<?php

use Carbon\Carbon;
use App\Support\Enums\Auth;

function generate_last_handshake_date(?string $string): ?Carbon
{
    if (!$string) {
        return null;
    }

    $matches = [];

    $match = preg_match(
        '/((?<weeks>\d*)w)?((?<days>\d*)d)?((?<hours>\d*)h)?((?<minutes>\d*)m)?((?<seconds>\d*)s)?/',
        $string,
        $matches);

    if (!$match) {
        return null;
    }

    $currentDate = Carbon::now();

    foreach ($matches as $key => $val) {
        if (!is_numeric($key) && $val) {
            $currentDate->sub($key, (int) $val);
        }
    }

    return $currentDate;
}

function auth_type(): Auth
{
    if (config('services.google.client_id')) {
        return Auth::Google;
    }

    return Auth::Form;
}
