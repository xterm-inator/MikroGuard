<?php

namespace App\RouterOS\Data;

readonly class Peer
{
    public string $name;

    public function __construct(
        $name,
        public string $allowedAddress,
        public string $publicKey,
        public string $interface,
        public string $presharedKey,
    )
    {
        $this->name = preg_replace('/[^A-Za-z0-9 .\-_()@+]/', '', $name);
    }
}
