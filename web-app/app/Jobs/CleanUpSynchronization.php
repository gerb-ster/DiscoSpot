<?php

namespace App\Jobs;

use App\Models\DiscogsRelease;
use App\Models\Playlist;
use App\Models\SpotifyTrack;
use App\Models\Synchronization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanUpSynchronization implements ShouldQueue
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
     */
    public function handle(): void
    {
        $synchronization = Synchronization::firstWhere('uuid', $this->synchronizationUuid);

        // remove any datasets which are not needed anymore
        DiscogsRelease::where('synchronization_uuid', $synchronization->uuid)->delete();
        SpotifyTrack::where('synchronization_uuid', $synchronization->uuid)->delete();
    }
}
