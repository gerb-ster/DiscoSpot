<?php

namespace App\Http\Controllers;

use App\Jobs\CleanUpSynchronization;
use App\Jobs\RemoveTracksFromPlaylist;
use App\Jobs\RetrieveDiscogsData;
use App\Jobs\RetrieveSpotifyData;
use App\Jobs\SyncPlaylist;
use App\Models\Playlist;
use App\Models\PlaylistType;
use App\Models\Synchronization;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Inertia\Response;
use Throwable;

class PlaylistController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return inertia('Playlist/Index', [
            'playlists' => Playlist::where('owner_id', Auth::user()->id)->get(),
            'user' => Auth::user()
        ]);
    }

    /**
     * @param Playlist $playlist
     * @return Response
     */
    public function show(Playlist $playlist): Response
    {
        return inertia('Playlist/Show', [
            'playlist' => $playlist,
            'user' => Auth::user()
        ]);
    }

    /**
     * @param Playlist $playlist
     * @return Application|RedirectResponse|Redirector
     */
    public function sync(Playlist $playlist): Redirector|RedirectResponse|Application
    {
        Artisan::call('sync:start', [
            'playlist_uuid' => $playlist->uuid
        ]);

        return redirect(route('playlist.index'))->with('success', 'Playlist Synchronization Started.');
    }

    /**
     * @param Request $request
     * @return Redirector|Application|RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        // create a playlist
        $playlist = new Playlist();
        $playlist->name = "My First Playlist!";
        $playlist->playlist_type_id = PlaylistType::BASED_ON_FOLDER;
        $playlist->last_sync = Carbon::now();
        $playlist->spotify_identifier = "0yAsN6AZziKCRLDPFJfWlh";
        $playlist->discogs_query_data = [
            'folder_id' => 1354190,
            'filters' => [
                'labels' => 'Blue Note'
            ]
        ];

        $playlist->save();

        return redirect(route('playlist.index'))->with('success', 'Playlist Created.');
    }
}
