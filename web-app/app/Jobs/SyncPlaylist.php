<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\Synchronization;
use App\Service\DiscogsApiClient;
use App\Service\DiscogsApiException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;

class SyncPlaylist implements ShouldQueue
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
     * @throws Exception|GuzzleException
     */
    public function handle(): void
    {
        foreach ($this->synchronization->discogs_data as $discogsEntry) {
            SearchSpotify::dispatch($this->playlist, $this->synchronization, $discogsEntry);
        }
    }
}
