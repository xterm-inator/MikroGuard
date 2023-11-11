<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Exceptions\HttpResponseException;
use App\Auth\ThrottlesLogins;
use App\Http\Requests\Auth\Login;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\User as UserResource;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * Max number of login attempts allowed.
     *
     * @var integer
     */
    protected int $maxAttempts = 5;

    /**
     * Number of minutes login attempts are throttled for.
     *
     * @var integer
     */
    protected int $decayMinutes = 5;

    /**
     * Handle an authentication attempt.
     *
     * @param Login $request
     * @param Auth $auth
     * @return UserResource
     *@throws HttpResponseException
     */
    public function __invoke(Login $request, Auth $auth)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($auth->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            return new UserResource($auth->user());
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['email' => [Lang::get('auth.failed')]])->status(422);
    }
}
