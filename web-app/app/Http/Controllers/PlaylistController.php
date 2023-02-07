<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SpotifyWebAPI\SpotifyWebAPI;

class PlaylistController extends Controller
{
    /**
     * @param Request $request
     * @return
     */
    public function index(Request $request): Factory|View|Application
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken(Auth::user()->spotify_token);

        // return view
        return view('playlist.index', [
            'output' => $api->search('artist:"donald byrd" album:"Street Lady"', 'album')
        ]);
    }

}
