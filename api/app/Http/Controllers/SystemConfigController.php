<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SystemConfigController extends Controller
{
    public function __invoke()
    {
        return new JsonResponse([
            'auth_type' => auth_type()
        ]);
    }
}
