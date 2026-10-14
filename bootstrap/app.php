<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Purge stale route and view cache if present to avoid RouteNotFoundException on shared hosting deployments
if (file_exists($routeCache = __DIR__.'/cache/routes-v7.php')) {
    @unlink($routeCache);
}

// 2. Ensure DocumentRoot contains proper .htaccess rewrite rules for Apache if needed
if (!empty($_SERVER['DOCUMENT_ROOT']) && is_dir($_SERVER['DOCUMENT_ROOT'])) {
    $docRootHtaccess = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . DIRECTORY_SEPARATOR . '.htaccess';
    $sourceHtaccess = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '.htaccess';
    if (file_exists($sourceHtaccess)) {
        if (!file_exists($docRootHtaccess) || filesize($docRootHtaccess) === 0) {
            @copy($sourceHtaccess, $docRootHtaccess);
        }
    }
}

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
            'rp' => \App\Http\Middleware\RpMiddleware::class,
            'arrivage' => \App\Http\Middleware\ArrivageMiddleware::class,
            'achat_cooperative' => \App\Http\Middleware\AchatCooperativeMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
