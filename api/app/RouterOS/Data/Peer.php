<?php

namespace App\RouterOS\Data;

class Peer
{
    public function __construct(
        public readonly string $allowedAddress,
        public readonly string $publicKey,
        public readonly string $interface,
        public readonly string $presharedKey,
    )
    {}
}
