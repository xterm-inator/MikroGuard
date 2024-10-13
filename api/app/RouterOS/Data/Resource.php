<?php

namespace App\RouterOS\Data;

readonly class Resource
{
    public string $version;

    public function __construct(string $version)
    {
        $this->version = explode(' ', $version)[0];
    }
}
