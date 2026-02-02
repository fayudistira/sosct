<?php

namespace App\Controllers\Auth;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;

class LoginController extends ShieldLogin
{
    /**
     * Displays the login form
     */
    public function loginView(): string|RedirectResponse
    {
        // If user is already logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            return redirect()->to(config('Auth')->redirects['login']);
        }

        return parent::loginView();
    }

    /**
     * Attempts to log the user in
     */
    public function loginAction(): RedirectResponse
    {
        // If user is already logged in, log them out first
        if (auth()->loggedIn()) {
            auth()->logout();
            session()->destroy();
        }

        return parent::loginAction();
    }
}
