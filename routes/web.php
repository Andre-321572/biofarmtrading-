<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/repair-database', function() {
    $users = \App\Models\User::all(['id', 'email', 'role']);
    $u = \App\Models\User::where('email', 'arrivage@biofarm.com')->first();
    $output = "Liste des utilisateurs sur le serveur :<br>";
    foreach($users as $user) {
        $output .= "- ID: {$user->id} | Email: {$user->email} | Role: {$user->role}<br>";
    }
    
    if($u) {
        $u->password = \Illuminate\Support\Facades\Hash::make('password123');
        $u->save();
        $output .= "<br><b>SUCCESS:</b> Mot de passe de arrivage@biofarm.com mis à jour à 'password123'.";
    } else {
        $output .= "<br><b>ERROR:</b> arrivage@biofarm.com non trouvé dans CETTE base de données.";
    }
    return $output;
});

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/product/{product}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->middleware(['auth', 'throttle:5,1'])->name('products.review.store');
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'send'])->middleware('throttle:3,1')->name('contact.send');

// Cart routes
Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
Route::post('/panier/ajouter/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/panier/modifier', [CartController::class, 'update'])->name('cart.update');
Route::delete('/panier/supprimer', [CartController::class, 'remove'])->name('cart.remove');

// Checkout routes
Route::get('/caisse', [OrderController::class, 'checkout'])->name('checkout.index');
Route::post('/caisse', [OrderController::class, 'store'])->middleware('throttle:5,1')->name('checkout.store');
Route::get('/commande/succes/{id}', [OrderController::class, 'success'])->name('order.success');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
        Route::get('/api/latest-order', function() {
            return \App\Models\Order::latest()->first() ?? ['id' => 0];
        })->name('api.latest-order');
        
        Route::get('/stock', [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/monthly', [\App\Http\Controllers\Admin\StockController::class, 'monthlyReport'])->name('stock.monthly');
        Route::get('/stock/supply-sheet', [\App\Http\Controllers\Admin\StockController::class, 'supplySheet'])->name('stock.supply_sheet');
        Route::post('/stock/supply-bulk', [\App\Http\Controllers\Admin\StockController::class, 'bulkStore'])->name('stock.supply_bulk');
        Route::post('/stock/supply', [\App\Http\Controllers\Admin\StockController::class, 'store'])->name('stock.store');
        
        // Attendance Management
        Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/update', [\App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');
        Route::get('/attendance/pdf', [\App\Http\Controllers\AttendanceController::class, 'pdfReport'])->name('attendance.pdf');
        Route::post('/workers', [\App\Http\Controllers\AttendanceController::class, 'storeWorkers'])->name('workers.store');
        Route::patch('/workers/{worker}', [\App\Http\Controllers\AttendanceController::class, 'updateWorker'])->name('workers.update');
        Route::delete('/workers/{worker}', [\App\Http\Controllers\AttendanceController::class, 'destroyWorker'])->name('workers.destroy');
    });

    // Arrivage Routes
    Route::middleware(['auth', 'arrivage'])->group(function () {
        Route::get('/arrivages', [\App\Http\Controllers\ArrivageController::class, 'index'])->name('arrivages.index');
        Route::get('/arrivages/create', [\App\Http\Controllers\ArrivageController::class, 'create'])->name('arrivages.create');
        Route::post('/arrivages', [\App\Http\Controllers\ArrivageController::class, 'store'])->name('arrivages.store');
        Route::get('/arrivages/{arrivage}', [\App\Http\Controllers\ArrivageController::class, 'show'])->name('arrivages.show');
        Route::get('/arrivages/{arrivage}/pdf', [\App\Http\Controllers\ArrivageController::class, 'pdf'])->name('arrivages.pdf');
        Route::get('/arrivages/{arrivage}/excel', [\App\Http\Controllers\ArrivageController::class, 'excel'])->name('arrivages.excel');
    });

    // Manager Routes
    Route::middleware(['auth', 'manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/sales', [\App\Http\Controllers\Manager\SalesController::class, 'index'])->name('sales.index');
        Route::post('/sales', [\App\Http\Controllers\Manager\SalesController::class, 'store'])->name('sales.store');
        Route::post('/sales/{id}/validate', [\App\Http\Controllers\Manager\SalesController::class, 'validateOrder'])->name('sales.validate');
        Route::get('/report', [\App\Http\Controllers\Manager\SalesController::class, 'report'])->name('sales.report');
        Route::get('/report/download', [\App\Http\Controllers\Manager\SalesController::class, 'downloadPDF'])->name('sales.report.pdf');
        
        // Manager Stock Routes
        Route::get('/stock', [\App\Http\Controllers\Manager\StockController::class, 'index'])->name('stock.index');
        Route::post('/stock/adjust', [\App\Http\Controllers\Manager\StockController::class, 'adjust'])->name('stock.adjust');
    });

    // RH Routes
    Route::middleware(['auth', 'rh'])->prefix('rh')->name('rh.')->group(function () {
        Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/update', [\App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');
        Route::get('/attendance/pdf', [\App\Http\Controllers\AttendanceController::class, 'pdfReport'])->name('attendance.pdf');
        Route::post('/workers', [\App\Http\Controllers\AttendanceController::class, 'storeWorkers'])->name('attendance.store_workers');
        Route::patch('/workers/{worker}', [\App\Http\Controllers\AttendanceController::class, 'updateWorker'])->name('attendance.update_worker');
        Route::delete('/workers/{worker}', [\App\Http\Controllers\AttendanceController::class, 'destroyWorker'])->name('attendance.destroy_worker');
    });
});

Route::get('/offline', function () {
    return view('offline');
});

require __DIR__.'/auth.php';

