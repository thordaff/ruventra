
<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SwitchAccountController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

// =====================================================
// =============== PASSWORD RESET ROUTES ==============
// =====================================================
Route::get('password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request');

Route::post('password/email', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return back()->with('status', __($status));
})->name('password.email');

Route::get('password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset');

Route::post('password/reset', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );
    return $status == Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

// Custom reset password page
Route::get('/reset-password', function () {
    return view('auth.reset-password');
});

// =====================================================
// =============== HOME & OTHER ROUTES ================
// =====================================================

// Catch-all: semua GET route dikembalikan ke Vue SPA
Route::get('/{any}', function () {
    return view('index');
})->where('any', '.*')->name('spa');

require __DIR__.'/settings.php';
