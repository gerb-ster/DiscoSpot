<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class AccountController extends Controller
{
    /**
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        // return view
        return inertia('Account/Index', [
            'user' => Auth::user(),
            'accountTypes' => AccountType::all()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function save(Request $request): RedirectResponse
    {
        ray($request->validate([
            'account_type_id' => ['required', 'max:50']
        ]));

        Auth::user()->update($request->validate([
            'account_type_id' => ['required', 'max:50']
        ]));

        return to_route('account.index');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function signOut(): Redirector|RedirectResponse|Application
    {
        Auth::logout();

        return redirect(route('landingpage'));
    }

    /**
     * Set the new Locale
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setLocale(Request $request): JsonResponse
    {
        $locale = $request->post('locale');

        Auth::user()->update([
            'locale' =>$locale
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
