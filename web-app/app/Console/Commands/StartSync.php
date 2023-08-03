<?php

namespace App\Console\Commands;

use App\Jobs\CleanUpSynchronization;
use App\Jobs\RemoveTracksFromPlaylist;
use App\Jobs\RetrieveDiscogsData;
use App\Jobs\RetrieveSpotifyData;
use App\Jobs\SyncPlaylist;
use App\Models\Playlist;
use App\Models\Synchronization;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Throwable;

class StartSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:start {playlist_uuid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Synchronization';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception|GuzzleException|Throwable
     */
    public function handle(): int
    {
        $uuid = $this->argument('playlist_uuid');

        $playlist = Playlist::firstWhere('uuid', $uuid);
        $playlist->last_sync = Carbon::now();
        $playlist->is_synchronizing = true;

        $playlist->save();

        $synchronization = new Synchronization([
            'playlist_id' => $playlist->id,
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
                        ray($e->getMessage());
                    })->dispatch();
                })->catch(function (Batch $batch, Throwable $e) {
                    ray($e->getMessage());
                })->dispatch();
            })->catch(function (Batch $batch, Throwable $e) {
                ray($e->getMessage());
            })->dispatch();
        })->catch(function (Batch $batch, Throwable $e) {
            ray($e->getMessage());
        })->finally(function (Batch $batch) use ($playlist) {
            // The batch has finished executing...
            $playlist->is_synchronizing = false;
            $playlist->save();
        })->dispatch();

        return 0;
    }
}
