<?php

namespace App\RouterOS;

use IPTools\Network;
use IPTools\Range;

class IPAddress extends RouterOS
{

    public static function getWireGuardAddresses(): ?Range
    {
        $routerOS = new self();

        $result = $routerOS->client->query('/ip/address/print', ['interface', config('services.wireguard.interface')])->read();

        if (!$result) {
            return null;
        }

        $network = Network::parse($result[0]['address']);
        $startIP = $network->getFirstIP()->next(2);
        $endIP = $network->getLastIP()->prev(1);
        return new Range($startIP, $endIP);
    }
}
