
<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SwitchAccountController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

// =====================================================
// =============== AUTH ROUTES ========================
// =====================================================
// Login (untuk modal)
Route::post('login', [LoginController::class, 'login']);
// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Switch account (role)
Route::middleware(['auth'])->get('switch-role/{role}', [SwitchAccountController::class, 'switch'])->name('switch.role');

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
// =============== DASHBOARD ROUTES ===================
// =====================================================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

// Dashboard (semua role, fitur dibedakan di blade)
Route::middleware(['auth'])->get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard.role');

// =====================================================
// =============== EVENT ROUTES =======================
// =====================================================
Route::get('/jelajah', function () {
    // Contoh data event, ganti dengan query ke database jika sudah ada model Event
    $events = [
        (object)[
            'id' => 1,
            'name' => 'Konser Musik Nusantara',
            'date' => '2026-04-10',
            'description' => 'Konser musik terbesar di Indonesia dengan berbagai artis nasional.',
            'image_url' => null,
        ],
        (object)[
            'id' => 2,
            'name' => 'Seminar Bisnis Kreatif',
            'date' => '2026-05-02',
            'description' => 'Seminar inspiratif untuk pelaku bisnis kreatif dan UMKM.',
            'image_url' => null,
        ],
        (object)[
            'id' => 3,
            'name' => 'Festival Kuliner Nusantara',
            'date' => '2026-06-15',
            'description' => 'Festival makanan khas dari seluruh penjuru Nusantara.',
            'image_url' => null,
        ],
    ];
    return view('jelajah', compact('events'));
});

// =====================================================
// =============== HOME & OTHER ROUTES ================
// =====================================================
Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

require __DIR__.'/settings.php';
