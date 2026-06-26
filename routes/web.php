<?php

use App\Http\Controllers\TwoFactorAccountController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->prefix('authenticator')->name('two-factor.')->group(function () {
    Route::get('/', [TwoFactorAccountController::class, 'index'])->name('index');
    Route::get('/create', [TwoFactorAccountController::class, 'create'])->name('create');
    Route::post('/', [TwoFactorAccountController::class, 'store'])->name('store');
    Route::get('/{account}/code', [TwoFactorAccountController::class, 'getCode'])->name('code');
    Route::delete('/{account}', [TwoFactorAccountController::class, 'destroy'])->name('destroy');
    Route::post('/export', [TwoFactorAccountController::class, 'export'])->name('export');
    Route::post('/import', [TwoFactorAccountController::class, 'import'])->name('import');
});

// Production migration route — run once after deploy
Route::get('/run-migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return response()->json([
        'status' => 'ok',
        'output' => Artisan::output(),
    ]);
})->middleware('auth');

// Debug: check DB connection and account counts (remove after debugging)
Route::get('/debug-db', function () {
    $user = auth()->user();
    $dbConfig = config('database.default');
    $dbHost = config("database.connections.{$dbConfig}.host", 'N/A');
    $dbName = config("database.connections.{$dbConfig}.database", 'N/A');
    $sessionDriver = config('session.driver');

    $info = [
        'user_id' => $user?->id,
        'user_email' => $user?->email,
        'db_driver' => $dbConfig,
        'db_host' => $dbHost,
        'db_name' => $dbName,
        'session_driver' => $sessionDriver,
        'accounts_count' => $user?->twoFactorAccounts()->count(),
        'accounts' => $user?->twoFactorAccounts->map(fn($a) => ['id' => $a->id, 'label' => $a->label]),
        'php_env' => app()->environment(),
    ];
    return response()->json($info);
})->middleware('auth');

require __DIR__.'/auth.php';
