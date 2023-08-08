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

    /**
     * @param Synchronization $synchronization
     * @param array $basicInformation
     * @return bool
     */
    private function passesFilter(Synchronization $synchronization, array $basicInformation): bool
    {
        // no filters? please continue
        if (empty($synchronization->playlist->discogs_query_data['filters'])) {
            return true;
        }

        foreach ($synchronization->playlist->discogs_query_data['filters'] as $key => $value) {
            if(array_key_exists($key, $basicInformation)) {
                if (is_array($basicInformation[$key])) {
                    foreach ($basicInformation[$key] as $entry) {
                        if($entry['name'] === $value) {
                            return true;
                        }
                    }
                }

                if(is_string($basicInformation[$key])) {
                    if($basicInformation[$key] === $value) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
