<?php

namespace App\RouterOS;

use RouterOS\Client;
use RouterOS\Laravel\Facade;

abstract class RouterOS
{
    protected Client $client;

    public function __construct()
    {
        $this->client = Facade::client();
    }
}
