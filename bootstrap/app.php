<?php

if (isset($_GET['repair'])) {
    try {
        $dbPath = __DIR__ . '/../database/database.sqlite';
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->query("SELECT id, email FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Base de données : $dbPath <br>";
        echo "Utilisateurs trouvés : <br>";
        foreach($users as $user) {
            echo "- {$user['email']} <br>";
        }
        
        $pass = password_hash('password123', PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'arrivage@biofarm.com'");
        $stmt->execute([$pass]);
        
        die("<br><b>SUCCESS:</b> Mot de passe réinitialisé pour arrivage@biofarm.com (password123)");
    } catch (Exception $e) {
        die("ERREUR PDO: " . $e->getMessage());
    }
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
