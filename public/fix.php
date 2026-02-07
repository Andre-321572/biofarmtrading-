<?php
// fix.php - Temporary repair script
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

echo "--- Diagnostic ---<br>";
try {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    echo "Caches cleared!<br>";
} catch (\Exception $e) {
    echo "Error clearing cache: " . $e->getMessage() . "<br>";
}

$users = User::all(['id', 'email', 'role']);
echo "Liste des utilisateurs :<br>";
foreach($users as $user) {
    echo "- ID: {$user->id} | Email: {$user->email} | Role: {$user->role}<br>";
}

$u = User::where('email', 'arrivage@biofarm.com')->first();
if($u) {
    $u->password = Hash::make('password123');
    $u->save();
    echo "<br><b>SUCCESS:</b> Mot de passe de arrivage@biofarm.com mis à jour à 'password123'.";
} else {
    echo "<br><b>ERROR:</b> arrivage@biofarm.com non trouvé.";
}
