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

class SyncPlaylist implements ShouldQueue
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
        $current = SpotifyTrack
            ::where('type', 'current')
            ->where('synchronization_id', $this->synchronization->id)
            ->get();

        if ($current->isNotEmpty()) {
            $this->synchronization->current_spotify_tracks_cache = $current->pluck('track_uri')->all();
            $this->synchronization->save();
        }

        foreach ($this->synchronization->discogs_data as $discogsEntry) {
            SearchSpotify::dispatch($this->playlist, $this->synchronization, $discogsEntry);
        }
    }
}
