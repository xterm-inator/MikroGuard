<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

function set_cors_headers($callback)
{
    $headers = [
        'Access-Control-Allow-Origin' => request()->header('Origin') ?? '*',
        'Access-Control-Allow-Credentials' => config('cors.supports_credentials'),
        'Access-Control-Allow-Methods' => config('cors.allowed_methods'),
        'Access-Control-Allow-Headers' => config('cors.allowed_headers'),
        'Access-Control-Expose-Headers' => config('cors.exposed_headers')
    ];

    foreach ($headers as $key => $value) {
        if (is_array($value)) {
            $callback($key, implode(', ', $value));
        } elseif (is_bool($value)) {
            if ($value === true) {
                $callback($key, 'true');
            }
        } else {
            $callback($key, $value);
        }
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    function dd(...$args)
    {
        set_cors_headers(function ($header, $value) {
            header(sprintf('%s: %s', $header, $value));
        });

        http_response_code(500);

        foreach ($args as $x) {
            (new Symfony\Component\VarDumper\VarDumper)->dump($x);
        }

        die(1);
    }
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
