<?php

namespace App\RouterOS;

use App\RouterOS\Data\Resource as ResourceData;

class Resource extends RouterOS
{

    public static function getRouterResource(): ResourceData
    {
        $routerOS = new self();

        $response = $routerOS->client->query('/system/resource/print')->read();
        if ($response) {
            return new ResourceData($response[0]['version']);
        }

        throw new \Exception('Could not find WireGuard interface');
    }
}
