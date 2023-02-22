<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\ResponseFactory;

class AccountController extends Controller
{
    /**
     * @return Response|ResponseFactory
     */
    public function settings(): Response|ResponseFactory
    {
        // return view
        return inertia('Account/Index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function signOut(): Redirector|RedirectResponse|Application
    {
        Auth::logout();

        return redirect(route('landingpage'));
    }
}
