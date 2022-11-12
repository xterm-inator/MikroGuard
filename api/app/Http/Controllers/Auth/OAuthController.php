<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider)
    {
        return response()->json([
            'url' => Socialite::driver($provider)->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * Obtain the user information from Google.
     *
     * @param $provider
     * @return Factory|View
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $user = $this->findUser($provider, $user);
        Auth::login($user);

        return view('oauth/callback', [
            'newUser' => $user->wasRecentlyCreated
        ]);
    }

    /**
     * @param  string $provider
     * @param  \Laravel\Socialite\Contracts\User $sUser
     * @return User|false
     */
    protected function findUser($provider, $sUser)
    {
        $oauthProvider = OAuthProvider::where('provider', $provider)
            ->where('provider_user_id', $sUser->getId())
            ->first();
        if ($oauthProvider) {
            $oauthProvider->update([
                'access_token' => $sUser->token,
                'refresh_token' => $sUser->refreshToken ?? null,
            ]);
            return $oauthProvider->user;
        }

        $email = $sUser->getEmail();

        $user = User::where('email', $email)->first();

        abort_if(!$user, 404, 'User not found');

        $user->oauthProviders()->create([
            'provider' => $provider,
            'provider_user_id' => $sUser->getId(),
            'access_token' => $sUser->token,
            'refresh_token' => $sUser->refreshToken ?? null,
        ]);

        return $user;
    }
}
