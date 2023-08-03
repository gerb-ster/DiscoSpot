<?php

namespace App\Jobs;

use App\Models\DiscogsRelease;
use App\Models\Playlist;
use App\Models\SpotifyTrack;
use App\Models\Statistic;
use App\Models\Synchronization;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use SpotifyWebAPI\SpotifyWebAPI;

class SearchSpotify implements ShouldQueue
{
    use Synchronize, Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $synchronizationUuid;

    /**
     * @var DiscogsRelease
     */
    private DiscogsRelease $discogsRelease;

    /**
     * @var SpotifyWebAPI
     */
    private SpotifyWebAPI $api;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $synchronizationUuid, DiscogsRelease $discogsRelease)
    {
        $this->synchronizationUuid = $synchronizationUuid;
        $this->discogsRelease = $discogsRelease;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $synchronization = $this->getSynchronization();

        if (Cache::has($this->discogsRelease->master_id)) {
            $cachedIds = Cache::get($this->discogsRelease->master_id);

            if ($cachedIds !== "not_found") {
                $this->api = new SpotifyWebAPI();
                $this->api->setAccessToken($synchronization->playlist->owner->spotify_token);

                $this->addTracksToPlaylist($synchronization, $cachedIds);
            }

            // store some statistics
            $this->storeStatistic(Statistic::RELEASE_NOT_FOUND, $this->discogsRelease->toArray());

            return;
        }

        $this->api = new SpotifyWebAPI();
        $this->api->setAccessToken($synchronization->playlist->owner->spotify_token);

        $searchResult = $this->api->search("album:{$this->discogsRelease->title} artist:{$this->discogsRelease->artist}" , [
            'type' => 'album'
        ]);

        // we've found something!
        if ($searchResult->albums->total > 0) {
            // just pick the first result
            $trackId = current($searchResult->albums->items)->id;

            $tracks = $this->api->getAlbumTracks($trackId);
            $tracksArray = [];

            foreach ($tracks->items as $track) {
                $tracksArray[] = $track->uri;
            }

            $this->addTracksToPlaylist($synchronization, $tracksArray);

            // store in cache for later use
            Cache::set($this->discogsRelease->master_id, $tracksArray);

            return;
        }

        // store some statistics
        $this->storeStatistic(Statistic::RELEASE_NOT_FOUND, $this->discogsRelease->toArray());

        // register as not found to cache
        Cache::set($this->discogsRelease->master_id, "not_found");
    }

    /**
     * @param Synchronization $synchronization
     * @param array $trackUris
     * @return void
     */
    private function addTracksToPlaylist(Synchronization $synchronization, array $trackUris): void
    {
        $currentTrackInPlaylist = $this->getCurrentTrackInPlaylist();

        // remove any tracks which are already in the playlist, no need to re-add them
        foreach ($trackUris as $key => $value) {
            $spotifyTrack = new SpotifyTrack([
                'track_uri' => $value,
                'type' => 'new',
                'synchronization_uuid' => $synchronization->uuid
            ]);

            $spotifyTrack->save();

            if (in_array($value, $currentTrackInPlaylist)) {
                unset($trackUris[$key]);
            }
        }

        if(count($trackUris) > 0) {
            // store some stats
            $this->storeStatistic(Statistic::TRACKS_ADDED_TO_PLAYLIST, $trackUris);

            // add track to playlist
            $this->api->addPlaylistTracks($synchronization->playlist->spotify_identifier, $trackUris);
        }
    }
}
