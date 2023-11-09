<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\Models\User;
use \App\Http\Resources\User as UserResource;
use App\RouterOS\WireGuard;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('index', User::class);

        $users = User::orderBy('email')
            ->with('config')
            ->get();

        $peers = WireGuard::getPeers();

        foreach ($users as $user) {
            $user->peer = $peers[$user->config->peer_public_key ?? null] ?? null;
        }

        return UserResource::collection($users);
    }

    public function store(StoreUser $request): UserResource
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
