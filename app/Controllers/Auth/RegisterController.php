<?php

namespace App\Controllers\Auth;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;

class RegisterController extends ShieldRegister
{
    /**
     * Displays the registration form
     */
    public function registerView(): string|RedirectResponse
    {
        // If user is already logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            return redirect()->to(config('Auth')->redirects['register']);
        }

        return parent::registerView();
    }

    /**
     * Attempts to register the user
     */
    public function registerAction(): RedirectResponse
    {
        // If user is already logged in, log them out first
        if (auth()->loggedIn()) {
            auth()->logout();
            session()->destroy();
        }

        return parent::registerAction();
    }
}
