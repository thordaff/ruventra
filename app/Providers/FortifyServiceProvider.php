<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Customize logout to redirect to home instead of the dashboard
        $this->app->instance(LogoutResponseContract::class, new class implements LogoutResponseContract {
            public function toResponse($request)
            {
                return $request->wantsJson()
                    ? response()->json('', 204)
                    : redirect()->route('home');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     * Since this is a pure Vue Router SPA, all auth page requests
     * simply load the SPA shell — Vue Router handles the actual routing.
     */
    private function configureViews(): void
    {
        $spa = fn () => view('index');

        Fortify::loginView($spa);
        Fortify::resetPasswordView($spa);
        Fortify::requestPasswordResetLinkView($spa);
        Fortify::verifyEmailView($spa);
        Fortify::registerView($spa);
        Fortify::twoFactorChallengeView($spa);
        Fortify::confirmPasswordView($spa);
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
