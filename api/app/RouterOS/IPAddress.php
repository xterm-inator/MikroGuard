<?php

namespace App\RouterOS;

use IPTools\IP;
use IPTools\Network;
use IPTools\Range;
use RouterOS\Query;

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

    public static function getWireGuardUsedAddresses(): array
    {
        $routerOS = new self();

        $query = new Query('/interface/wireguard/peers/print');
        $query->where('interface', config('services.wireguard.interface'));

        $response = $routerOS->client->query($query)->read();

        if (!count($response)) {
            return [];
        }

        $ips = [];

        foreach ($response as $peer) {
            $ips[] = Range::parse($peer['allowed-address']);
        }

        return $ips;
    }
}
