<?php

namespace App\Http\Controllers;

use App\Jobs\RemoveTracksFromPlaylist;
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
use Illuminate\Support\Facades\Bus;
use Throwable;

class PlaylistController extends Controller
{
    /**
     * @param Request $request
     * @return
     */
    public function index(Request $request): Factory|View|Application
    {
        // return view
        return view('playlist.index', []);
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
        $playlist->spotify_identifier = "1V5WugadOoDMMhBb26zR4k";
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
            'current_spotify_tracks_cache' => []
        ]);

        $synchronization->save();

        Bus::chain([
            new RetrieveDiscogsData($playlist, $synchronization),
            new RetrieveSpotifyData($playlist, $synchronization),
            new SyncPlaylist($playlist, $synchronization),
            new RemoveTracksFromPlaylist($playlist, $synchronization)
        ])->catch(function (Throwable $e) {
            // A job within the chain has failed...
        })->dispatch();

        // return view
        return view('playlist.test', [
        ]);
    }
}
