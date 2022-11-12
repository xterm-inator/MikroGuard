<?php

namespace App\Http\Controllers;

use App\Http\Resources\Config as ConfigResource;
use App\Models\User;
use App\RouterOS\WireGuard;
use App\Services\CreatesUserWireGuardConfig;
use Illuminate\Support\Facades\App;

class ConfigController extends Controller
{
    public function index(User $user): ConfigResource
    {
        $this->authorize('config', $user);

        abort_if(!$user->config, 404);

        $peer = WireGuard::getPeer($user->config->peer_public_key);

        // if peer is null means it has been deleted from the router, we will delete the local config.
        if (!$peer) {
            $user->config->delete();
            abort(404);
        }

        $user->config->details = $peer;

        return new ConfigResource($user->config);
    }

    public function store(User $user): ConfigResource
    {
        $this->authorize('config', $user);

        if ($user->config) {
            $user->config->delete();
        }

        $config = App::call(new CreatesUserWireGuardConfig($user));

        $peer = WireGuard::getPeer($config->peer_public_key);

        $config->details = $peer;

        return new ConfigResource($config);
    }

    public function destroy(User $user)
    {
        $this->authorize('config', $user);

        if ($user->config) {
            $user->config->delete();
        }

        return response()->noContent();
    }
}
