<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\SpotifyTrack;
use App\Models\Synchronization;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SpotifyWebAPI\SpotifyWebAPI;

class RemoveTracksFromPlaylist implements ShouldQueue
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
    public function handle(): void
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken($this->playlist->owner->spotify_token);

        $pluckedCurrent = SpotifyTrack
            ::where('type', 'current')
            ->where('synchronization_id', $this->synchronization->id)
            ->get();

        $pluckedNew = SpotifyTrack
            ::where('type', 'new')
            ->where('synchronization_id', $this->synchronization->id)
            ->get();


        $removedTracks = array_diff(
            $pluckedCurrent->pluck('track_uri')->all(),
            $pluckedNew->pluck('track_uri')->all()
        );

        $tracks = ['tracks' => []];

        foreach ($removedTracks as $trackUri) {
            $tracks['tracks'][] = [
                'uri' => $trackUri
            ];
        }

        $api->deletePlaylistTracks($this->playlist->spotify_identifier, $tracks);
    }
}
