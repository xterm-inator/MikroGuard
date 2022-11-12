<?php
/*
 * Reference: https://github.com/kiler129/mikrotik-auto-wireguard/blob/master/src/WireGuard/KeyGenerator.php
 */
namespace App\Services;

use Exception;
use SodiumException;

class KeyGenerator
{
    /**
     * @return array{private: string, public: string}
     * @throws SodiumException
     */
    public static function generateBase64Keypair(): array
    {
        $keypair = sodium_crypto_kx_keypair();

        return [
            'private' => base64_encode(sodium_crypto_kx_secretkey($keypair)),
            'public' => base64_encode(sodium_crypto_kx_publickey($keypair)),
        ];
    }

    /**
     * @throws Exception
     */
    public static function generateBase64Psk(): string
    {
        //256 bit is the default (and only) wg psk length
        return base64_encode(random_bytes(32));
    }
}
