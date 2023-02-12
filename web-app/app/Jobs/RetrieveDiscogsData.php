<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\Synchronization;
use App\Service\DiscogsApiClient;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

class RetrieveDiscogsData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Playlist
     */
    private Playlist $playlist;

    /**
     * @var Synchronization
     */
    private Synchronization $synchronization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist, Synchronization $synchronization)
    {
        $this->playlist = $playlist;
        $this->synchronization = $synchronization;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    #[NoReturn] public function handle(): void
    {
        $discogsApi = new DiscogsApiClient(
            $this->playlist->owner->discogs_token,
            $this->playlist->owner->discogs_secret
        );

        $folder_id = $this->playlist->discogs_query_data['folder_id'];
        $folder = "/users/{$this->playlist->owner->discogs_username}/collection/folders/{$folder_id}";

        $metadata = $discogsApi->get($folder);
        $jobs = [];

        for ($x = 1; $x <= ceil($metadata['count'] / 100); $x++) {
            $jobs[] = new RetrieveDiscogsFolderContent(
                $this->playlist,
                $this->synchronization,
                $folder . "/releases",
                $x
            );
        }

        $batch = Bus::batch($jobs)->then(function (Batch $batch) {

        })->catch(function (Batch $batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function (Batch $batch) {
            // The batch has finished executing...
        })->dispatch();
    }
}
