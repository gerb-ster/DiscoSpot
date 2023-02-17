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
use App\Models\SynchronizationStatus;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
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
     * @throws Throwable
     */
    public function test(Request $request): Factory|View|Application
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

        $synchronization = new Synchronization([
            'playlist_id' => $playlist->id,
            'status_id' => SynchronizationStatus::READY_FOR_START,
            'statistics' => []
        ]);
        $synchronization->save();

        Bus::batch([
            new RetrieveDiscogsData($synchronization->uuid)
        ])->then(function (Batch $batch) use ($synchronization){
            // All jobs completed successfully...
            Bus::batch([
                new RetrieveSpotifyData($synchronization->uuid)
            ])->then(function (Batch $batch) use ($synchronization){
                Bus::batch([
                    new SyncPlaylist($synchronization->uuid)
                ])->then(function (Batch $batch) use ($synchronization){
                    Bus::chain([
                        new RemoveTracksFromPlaylist($synchronization->uuid),
                        new CleanUpSynchronization($synchronization->uuid)
                    ])->catch(function (Throwable $e) {
                        // A job within the chain has failed...
                    })->dispatch();
                })->dispatch();
            })->dispatch();
        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->dispatch();

        return view('playlist.test', [
        ]);
    }
}
