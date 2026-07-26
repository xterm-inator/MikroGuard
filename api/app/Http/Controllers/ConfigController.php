<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConfigRequest;
use App\Http\Resources\ConfigResource as ConfigResource;
use App\Models\Peer;
use App\Models\User;
use App\RouterOS\WireGuard;
use App\Services\CreatesUserWireGuardConfig;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\App;

class ConfigController extends Controller
{
    public function index(User $user): AnonymousResourceCollection
    {
        $this->authorize('config', $user);

        $peers = $user->peers->map(function (Peer $peer) {
            $peer->details = WireGuard::getPeer($peer->peer_public_key);
            return $peer;
        })?->filter(function (Peer $peer) {
            // if details is null means it has been deleted from the router, we will delete the local peer.
            if (!$peer->details) {
                $peer->delete();
                return false;
            }
            return true;
        });

        return ConfigResource::collection($peers);
    }

    public function store(StoreConfigRequest $request, User $user): ConfigResource
    {
        $config = App::call(new CreatesUserWireGuardConfig($user, $request->safe()->input('name')));

        $peer = WireGuard::getPeer($config->peer_public_key);

        $config->details = $peer;

        return new ConfigResource($config);
    }

    public function destroy(User $user, Peer $config): ConfigResource
    {
        $this->authorize('config', $user);

        $config->delete();

        return response()->noContent();
    }
}
