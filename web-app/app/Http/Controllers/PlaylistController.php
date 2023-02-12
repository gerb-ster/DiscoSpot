<?php

namespace App\Http\Controllers;

use App\Jobs\RetrieveDiscogsData;
use App\Jobs\RetrieveSpotifyData;
use App\Jobs\SyncPlaylist;
use App\Models\Playlist;
use App\Models\PlaylistType;
use App\Models\Synchronization;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use SpotifyWebAPI\SpotifyWebAPI;
use Throwable;

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

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function test(Request $request): Factory|View|Application
    {
        // create a playlist
        $playlist = new Playlist();
        $playlist->name = "My First Playlist!";
        $playlist->playlist_type_id = PlaylistType::BASED_ON_FOLDER;
        $playlist->last_sync = Carbon::now();
        $playlist->spotify_identifier = "1ENsjXMnWaa3F5czbOE8g4";
        $playlist->discogs_query_data = [
            'folder_id' => 1354190,
            'filters' => [
                'labels' => 'Blue Note'
            ]
        ];

        $playlist->save();

        $synchronization = new Synchronization([
            'associated_playlist_id' => $playlist->id,
            'discogs_data' => [],
            'spotify_data' => []
        ]);

        $synchronization->save();

        Bus::chain([
            new RetrieveDiscogsData($playlist, $synchronization),
            new RetrieveSpotifyData($playlist, $synchronization),
            new SyncPlaylist($playlist, $synchronization),
        ])->catch(function (Throwable $e) {
            // A job within the chain has failed...
        })->dispatch();

        // return view
        return view('playlist.test', [
        ]);
    }
}
