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
    Route::get('/archived', [TwoFactorAccountController::class, 'archived'])->name('archived');
    Route::get('/create', [TwoFactorAccountController::class, 'create'])->name('create');
    Route::post('/', [TwoFactorAccountController::class, 'store'])->name('store');
    Route::get('/{account}/code', [TwoFactorAccountController::class, 'getCode'])->name('code');
    Route::delete('/{account}', [TwoFactorAccountController::class, 'destroy'])->name('destroy');
    Route::post('/{account}/restore', [TwoFactorAccountController::class, 'restore'])->name('restore')->withTrashed();
    Route::delete('/{account}/force-delete', [TwoFactorAccountController::class, 'forceDelete'])->name('force-delete')->withTrashed();
    Route::post('/export', [TwoFactorAccountController::class, 'export'])->name('export');
    Route::post('/import', [TwoFactorAccountController::class, 'import'])->name('import');
});

// Production migration route — run once after deploy.
// Guarded by DEPLOY_TOKEN so only the deployer can trigger.
Route::get('/run-migrations', function (\Illuminate\Http\Request $request) {
    $token = env('DEPLOY_TOKEN');
    abort_unless($token && hash_equals($token, (string) $request->query('token')), 403);
    Artisan::call('migrate', ['--force' => true]);
    return response()->json([
        'status' => 'ok',
        'output' => Artisan::output(),
    ]);
});

require __DIR__.'/auth.php';
