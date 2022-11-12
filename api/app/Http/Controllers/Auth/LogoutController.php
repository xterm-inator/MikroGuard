<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function __invoke(): Response
    {
        Session::flush();
        Auth::guard('web')->logout();

        return response()->noContent();
    }
}
