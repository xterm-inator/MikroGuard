<?php

namespace App\RouterOS\Data;

class WireGuardInterface
{
    public function __construct(public readonly string $publicKey)
    {}
}
