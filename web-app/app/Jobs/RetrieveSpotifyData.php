<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\Synchronization;
use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use JetBrains\PhpStorm\NoReturn;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Throwable;

class RetrieveSpotifyData implements ShouldQueue
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
     */
    #[NoReturn] public function handle(): void
    {
        $session = new Session(
            env('SPOTIFY_CLIENT_ID'),
            env('SPOTIFY_CLIENT_SECRET'),
            env('SPOTIFY_REDIRECT_URI')
        );

        $session->refreshAccessToken($this->playlist->owner->spotify_refresh_token);

        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();

        $api = new SpotifyWebAPI();
        $api->setAccessToken($accessToken);

        $this->playlist->owner->spotify_token = $accessToken;
        $this->playlist->owner->spotify_refresh_token = $refreshToken;
        $this->playlist->owner->save();

        if(is_null($this->playlist->spotify_identifier)) {
            $spotifyPlaylist = $api->createPlaylist([
                'name' => $this->playlist->name,
                'description' => 'DiscoSpot created playlist!'
            ]);

            $this->playlist->spotify_identifier = $spotifyPlaylist->id;
            $this->playlist->save();
        }

        $metadata = $api->getPlaylist($this->playlist->spotify_identifier);
        $jobs = [];

        if ($metadata->tracks->total > 0) {
            for ($x = 0; $x <= ceil($metadata->tracks->total / 100); $x++) {
                $jobs[] = new RetrieveSpotifyTracks(
                    $this->playlist,
                    $this->synchronization,
                    $x
                );
            }

            Bus::batch($jobs)->then(function (Batch $batch) {

            })->catch(function (Batch $batch, Throwable $e) {
                // First batch job failure detected...
            })->finally(function (Batch $batch) {
                // The batch has finished executing...
            })->dispatch();
        }
    }
}
