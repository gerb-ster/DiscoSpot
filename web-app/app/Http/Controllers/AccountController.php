<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * @return View
     */
    public function settings(): View
    {
        // return view
        return view('account.settings', [
        ]);
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function signOut(): Redirector|RedirectResponse|Application
    {
        Auth::logout();
        return redirect(route('welcome'));
    }
}
