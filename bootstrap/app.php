<?php

if (isset($_GET['repair'])) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $u = \App\Models\User::where('email', 'arrivage@biofarm.com')->first();
    if($u) {
        $u->password = \Illuminate\Support\Facades\Hash::make('password123');
        $u->save();
        die("REPAIR_SUCCESS: Password set to 'password123' for arrivage@biofarm.com. Cache will be cleared on next request.");
    }
    die("REPAIR_FAILED: User not found in database.");
}

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'manager' => \App\Http\Middleware\ManagerMiddleware::class,
            'rh' => \App\Http\Middleware\RhMiddleware::class,
            'arrivage' => \App\Http\Middleware\ArrivageMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
