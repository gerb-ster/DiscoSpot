<?php

namespace App\Jobs;

use App\Models\Synchronization;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Throwable;

class RetrieveSpotifyData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $synchronizationUuid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $synchronizationUuid)
    {
        $this->synchronizationUuid = $synchronizationUuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function handle(): void
    {
        $synchronization = Synchronization::firstWhere('uuid', $this->synchronizationUuid);

        $session = new Session(
            env('SPOTIFY_CLIENT_ID'),
            env('SPOTIFY_CLIENT_SECRET'),
            env('SPOTIFY_REDIRECT_URI')
        );

        $session->refreshAccessToken($synchronization->playlist->owner->spotify_refresh_token);

        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();

        $api = new SpotifyWebAPI();
        $api->setAccessToken($accessToken);

        $synchronization->playlist->owner->spotify_token = $accessToken;
        $synchronization->playlist->owner->spotify_refresh_token = $refreshToken;
        $synchronization->playlist->owner->save();

        if(is_null($synchronization->playlist->spotify_identifier)) {
            $spotifyPlaylist = $api->createPlaylist([
                'name' => $synchronization->playlist->name,
                'description' => 'DiscoSpot created playlist!'
            ]);

            $synchronization->playlist->spotify_identifier = $spotifyPlaylist->id;
            $synchronization->playlist->save();
        }

        $metadata = $api->getPlaylist($synchronization->playlist->spotify_identifier);

        // hydrate the batch with jobs
        if ($metadata->tracks->total > 0) {
            for ($x = 0; $x <= ceil($metadata->tracks->total / 100); $x++) {
                $this->batch()->add(new RetrieveSpotifyTracks($synchronization, $x));
            }
        }
    }
}
