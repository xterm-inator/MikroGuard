<?php

namespace App\RouterOS\Data;

readonly class Peer
{
    public function __construct(
        public string $allowedAddress,
        public string $publicKey,
        public string $interface,
        public string $presharedKey,
    )
    {}
}
