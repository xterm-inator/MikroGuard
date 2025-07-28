<?php

namespace App\Services;

use App\Models\Peer;
use App\Models\User;
use App\RouterOS\IPAddress;
use App\RouterOS\WireGuard;
use Exception;
use IPTools\IP;
use SodiumException;

readonly class CreatesUserWireGuardConfig
{
    public function __construct(private User $user, private string $name)
    {}

    /**
     * @throws SodiumException
     * @throws Exception
     */
    public function __invoke(): Peer
    {
        $ip = $this->getNextAvailableIP();
        $interface = WireGuard::getWireGuardInterface();
        $key = KeyGenerator::generateBase64Keypair();
        $psk = KeyGenerator::generateBase64Psk();

        $config = new Peer([
            'peer_name' => "{$this->user->username} ($this->name)",
            'peer_private_key' => $key['private'],
            'peer_public_key' => $key['public'],
            'peer_preshared_key' => $psk,
            'server_name' => config('services.wireguard.server_name'),
            'server_public_key' => $interface->publicKey,
            'endpoint' => config('services.wireguard.endpoint'),
            'dns' => config('services.wireguard.dns'),
            'allowed_ips' => config('services.wireguard.allowed_ips'),
            'address' => (string)$ip,
        ]);

        $config->user_id = $this->user->id;

        $config->save();

        return $config;
    }

    /**
     * Find the next available IP from MikroTik for the vpn subnet
     *
     * @throws Exception
     */
    private function getNextAvailableIP(): IP
    {
        $range = IPAddress::getWireGuardAddresses();
        $usedIPs = IPAddress::getWireGuardUsedAddresses();
        $ips = [];

        foreach ($usedIPs as $ipRange) {
            foreach ($ipRange as $ip) {
                $ips[] = $ip;
            }
        }

        foreach ($range as $ip) {
            $notFound = true;
            foreach ($ips as $existingIP) {
                if (!$notFound) {
                    continue;
                }
                $notFound = !((string)$ip == (string)$existingIP);
            }
            if ($notFound) {
                return $ip;
            }
        }

        throw new Exception('Could not find available IP for client');
    }
}
