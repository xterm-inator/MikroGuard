<?php

namespace App\RouterOS\Data;

readonly class WireGuardInterface
{
    public function __construct(public string $publicKey)
    {}
}
