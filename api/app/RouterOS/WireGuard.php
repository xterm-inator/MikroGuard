<?php

namespace App\RouterOS;

use App\RouterOS\Data\Peer;
use App\RouterOS\Data\WireGuardInterface;
use RouterOS\Query;

class WireGuard extends RouterOS
{
    public static function getWireGuardInterface(): WireGuardInterface
    {
        $routerOS = new self();

        $response = $routerOS->client->query('/interface/wireguard/print', ['name', config('services.wireguard.interface')])->read();

        if ($response) {
            return new WireGuardInterface($response[0]['public-key']);
        }

        throw new \Exception('Could not find WireGuard interface');
    }

    public static function getPeer(string $publicKey): ?array
    {
        $routerOS = new self();

        $query = new Query('/interface/wireguard/peers/print');
        $query->where('public-key', $publicKey)
            ->where('interface', config('services.wireguard.interface'));

        $response = $routerOS->client->query($query)->read();

        if (!count($response)) {
            return null;
        }

        return $response[0];
    }

    public static function getPeers(): array
    {
        $routerOS = new self();

        $query = new Query('/interface/wireguard/peers/print');
        $query->where('interface', config('services.wireguard.interface'));

        $response = $routerOS->client->query($query)->read();

        if (!count($response)) {
            return [];
        }

        $peers = [];

        foreach ($response as $peer) {
            $peers[$peer['public-key']] = $peer;
        }

        return $peers;
    }

    public static function createPeer(Peer $peer): void
    {
        $routerOS = new self();

        $query = new Query('/interface/wireguard/peers/add');
        $query->equal('allowed-address',  $peer->allowedAddress)
            ->equal('interface', $peer->interface)
            ->equal('public-key', $peer->publicKey)
            ->equal('preshared-key', $peer->presharedKey);

        $routerOS->client->query($query)->read();
    }

    public static function deletePeer(string $publicKey): void
    {
        $routerOS = new self();

        $peer = self::getPeer($publicKey);

        if ($peer) {
            $query = new Query('/interface/wireguard/peers/remove');
            $query->equal('.id', $peer['.id']);

            $routerOS->client->query($query)->read();
        }
    }
}
