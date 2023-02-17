<?php

namespace App\Jobs;

use App\Models\DiscogsRelease;
use App\Models\SpotifyTrack;
use App\Models\Synchronization;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SyncPlaylist implements ShouldQueue
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

        $current = SpotifyTrack
            ::where('type', 'current')
            ->where('synchronization_uuid', $synchronization->uuid)
            ->get();

        $currentTracksInPlaylist = $current->pluck('track_uri')->all();

        $discogsReleases = DiscogsRelease
            ::where('synchronization_uuid', $synchronization->uuid)
            ->get();

        foreach ($discogsReleases as $discogsRelease) {
            $this->batch()->add(new SearchSpotify(
                $synchronization->uuid,
                $discogsRelease,
                $currentTracksInPlaylist
            ));
        }
    }
}
