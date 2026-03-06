
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
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

// API: get new CSRF token (untuk SPA setelah logout)
Route::get('/api/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

// =====================================================
// =============== HOME & OTHER ROUTES ================
// =====================================================

// Catch-all: semua GET route dikembalikan ke Vue SPA
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*')->name('spa');

require __DIR__.'/settings.php';
