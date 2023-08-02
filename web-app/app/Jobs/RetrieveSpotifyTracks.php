<?php

namespace App\Jobs;

use App\Models\SpotifyTrack;
use App\Models\Synchronization;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SpotifyWebAPI\SpotifyWebAPI;

class RetrieveSpotifyTracks implements ShouldQueue
{
    use Synchronize, Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $synchronizationUuid;

    /**
     * @var int
     */
    private int $offset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $synchronizationUuid, int $offset)
    {
        $this->synchronizationUuid = $synchronizationUuid;
        $this->offset = $offset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $synchronization = $this->getSynchronization();

        $api = new SpotifyWebAPI();
        $api->setAccessToken($synchronization->playlist->owner->spotify_token);

        $tracks = $api->getPlaylistTracks($synchronization->playlist->spotify_identifier, [
            'offset' => $this->offset * 100,
            'limit' => 100
        ]);

        foreach ($tracks->items as $value) {
            $spotifyTrack = new SpotifyTrack([
                'track_uri' => $value->track->uri,
                'type' => 'current',
                'synchronization_uuid' => $synchronization->uuid
            ]);

            $spotifyTrack->save();
        }
    }
}
