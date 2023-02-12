<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\Synchronization;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SpotifyWebAPI\SpotifyWebAPI;

class RetrieveSpotifyTracks implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Playlist
     */
    private Playlist $playlist;

    /**
     * @var Synchronization
     */
    private Synchronization $synchronization;

    /**
     * @var int
     */
    private int $offset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist, Synchronization $synchronization, int $offset)
    {
        $this->playlist = $playlist;
        $this->synchronization = $synchronization;
        $this->offset = $offset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken($this->playlist->owner->spotify_token);

        $tracks = $api->getPlaylistTracks($this->playlist->spotify_identifier, [
            'offset' => $this->offset,
            'limit' => 100
        ]);

        $trackUris = [];
        foreach ($tracks->items as $entry) {
            $trackUris[] = $entry->track->uri;
        }

        $this->synchronization->refresh();
        $this->synchronization->spotify_data = array_merge($this->synchronization->spotify_data, $trackUris);
        $this->synchronization->save();
    }
}
