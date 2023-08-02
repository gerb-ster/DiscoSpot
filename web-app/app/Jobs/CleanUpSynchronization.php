<?php

namespace App\Jobs;

use App\Models\DiscogsRelease;
use App\Models\SpotifyTrack;
use App\Models\Statistic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanUpSynchronization implements ShouldQueue
{
    use Synchronize, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $synchronization = $this->getSynchronization();

        // remove any datasets which are not needed anymore
        DiscogsRelease::where('synchronization_uuid', $synchronization->uuid)->delete();
        SpotifyTrack::where('synchronization_uuid', $synchronization->uuid)->delete();

        // collect all statistics
        $collectedStats = [];
        $statistics = Statistic::where('synchronization_uuid', $synchronization->uuid)->get();

        foreach ($statistics as $statistic) {
            if (array_key_exists($statistic->type, $collectedStats)) {
                $collectedStats[$statistic->type] = array_merge(
                    $collectedStats[$statistic->type],
                    $statistic->value
                );
            }

            $collectedStats[$statistic->type] = [
                'time_stamp' => $statistic->time_stamp,
                'value' => $statistic->value
            ];
        }

        $synchronization->update([
            'statistics' => $collectedStats
        ]);

        // now we can delete the statistics
        Statistic::where('synchronization_uuid', $synchronization->uuid)->delete();
    }
}
