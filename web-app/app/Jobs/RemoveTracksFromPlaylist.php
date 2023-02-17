<?php

namespace App\Jobs;

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
     */
    public function handle(): void
    {
        $synchronization = Synchronization::firstWhere('uuid', $this->synchronizationUuid);

        $api = new SpotifyWebAPI();
        $api->setAccessToken($synchronization->playlist->owner->spotify_token);

        $pluckedCurrent = SpotifyTrack
            ::where('type', 'current')
            ->where('synchronization_uuid', $synchronization->uuid)
            ->get();

        $pluckedNew = SpotifyTrack
            ::where('type', 'new')
            ->where('synchronization_uuid', $synchronization->uuid)
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

        $api->deletePlaylistTracks($synchronization->playlist->spotify_identifier, $tracks);
    }
}
