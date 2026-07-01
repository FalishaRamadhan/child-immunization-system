<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'No account exists for this Google account. Please contact the administrator.',
                    ]);
            }

            if (!$user->is_active) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'This account has been deactivated. Contact your administrator.',
                    ]);
            }

            Auth::login($user);

            request()->session()->regenerate();

            AuditService::log('login', 'User logged in using Google');

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Google authentication failed. Please try again.',
                ]);
        }
    }
}