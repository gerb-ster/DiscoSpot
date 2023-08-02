<?php

namespace App\Jobs;

use App\Models\SpotifyTrack;
use App\Models\Statistic;
use App\Models\Synchronization;
use Carbon\Carbon;

trait Synchronize
{
    /**
     * @return Synchronization
     */
    private function getSynchronization(): Synchronization
    {
        return Synchronization::firstWhere('uuid', $this->synchronizationUuid);
    }

    /**
     * @param string $type
     * @param mixed $value
     * @return void
     */
    private function storeStatistic(string $type, mixed $value): void
    {
        $statistic = new Statistic([
            'synchronization_uuid' => $this->synchronizationUuid,
            'time_stamp' => Carbon::now()->toDateTimeString(),
            'type' => $type,
            'value' => $value
        ]);

        $statistic->save();
    }

    /**
     * @return array
     */
    private function getCurrentTrackInPlaylist(): array
    {
        $current = SpotifyTrack
            ::where('type', 'current')
            ->where('synchronization_uuid', $this->synchronizationUuid)
            ->get();

        return $current->pluck('track_uri')->all();
    }
}
