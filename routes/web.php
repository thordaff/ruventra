
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NavItemController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Http\Request;

// =====================================================
// =============== AUTH ROUTES ========================
// =====================================================
// Login (untuk Vue SPA modal)
Route::post('login', [LoginController::class, 'login']);
// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// Register
Route::post('register', [RegisteredUserController::class, 'store']);

// API: get current authenticated user (untuk Vue SPA)
Route::middleware('auth')->get('/api/user', function (Request $request) {
    return response()->json($request->user()->load('roles'));
});

// API: get nav items filtered by user's roles
Route::middleware('auth')->get('/api/nav-items', [NavItemController::class, 'index']);

// API: get new CSRF token (untuk SPA setelah logout)
Route::get('/api/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// =====================================================
// =============== DEVELOPER / ADMIN API ==============
// =====================================================
Route::middleware(['auth'])->prefix('api')->group(function () {
    // System Logs (developer only – enforced in Vue route guard)
    Route::get('/system-logs', [SystemLogController::class, 'index']);

    // User management (suspend / unsuspend) – developer / superAdmin
    Route::get('/users', [UserManagementController::class, 'index']);
    Route::post('/users/{user}/suspend', [UserManagementController::class, 'suspend']);
    Route::post('/users/{user}/unsuspend', [UserManagementController::class, 'unsuspend']);
});

// =====================================================
// =============== HOME & OTHER ROUTES ================
// =====================================================

// Catch-all: semua GET route dikembalikan ke Vue SPA
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*')->name('spa');

require __DIR__.'/settings.php';
