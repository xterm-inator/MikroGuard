<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use \App\Http\Resources\UserResource as UserResource;
use App\RouterOS\WireGuard;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('index', User::class);

        $users = User::orderBy('username')
            ->with('peers')
            ->get();

        $peers = WireGuard::getPeers();

        foreach ($users as $user) {
            $pubKeys = $user->peers->pluck('peer_public_key')->toArray();
            $user->router_peers = collect(array_filter($peers, fn ($key) => in_array($key, $pubKeys), ARRAY_FILTER_USE_KEY));
        }

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create($request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user): Response
    {
        $this->authorize('delete', $user);

        $user->config?->delete();
        $user->oauthProviders()->delete();

        $user->delete();

        return response()->noContent();
    }
}
