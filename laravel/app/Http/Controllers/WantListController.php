<?php

namespace App\Http\Controllers;

use App\Models\WantList;
use App\Service\DiscogsApiClient;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class WantListController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $discogs = new DiscogsApiClient();

        $data = $discogs->get('marketplace/search?=1011707', [
            'release_id' => 1011707
        ]);
        /*
                WantList::create([
                    'username' => 'gerbster',
                    'wants' => $data['wants']
                ]);
        */
       // $dbData = WantList::all();

        // return view
        return response()->json($data);
    }

    /**
     * @return View
     */
    public function show(): View
    {
        // return view
        return view('wantlist.show', [
        ]);
    }
}
