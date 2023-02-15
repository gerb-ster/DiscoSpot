<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\SpotifyTrack;
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
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Playlist
     */
    private Playlist $playlist;

    /**
     * @var Synchronization
     */
    private Synchronization $synchronization;

    /**
     * @var array
     */
    private array $discogsEntry;

    /**
     * @var SpotifyWebAPI
     */
    private SpotifyWebAPI $api;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist, Synchronization $synchronization, array $discogsEntry)
    {
        $this->playlist = $playlist;
        $this->synchronization = $synchronization;
        $this->discogsEntry = $discogsEntry;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        if (Cache::has($this->discogsEntry['master_id'])) {
            $cachedIds = Cache::get($this->discogsEntry['master_id']);

            if ($cachedIds !== "not_found") {
                $this->api = new SpotifyWebAPI();
                $this->api->setAccessToken($this->playlist->owner->spotify_token);

                $this->addTracksToPlaylist($cachedIds);
            }

            return;
        }

        $this->api = new SpotifyWebAPI();
        $this->api->setAccessToken($this->playlist->owner->spotify_token);

        $primaryArtist = current($this->discogsEntry['artists'])['name'];

        $searchResult = $this->api->search("album:{$this->discogsEntry['title']} artist:{$primaryArtist}" , [
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

            $this->addTracksToPlaylist($tracksArray);

            // store in cache for later use
            Cache::set($this->discogsEntry['master_id'], $tracksArray);

            return;
        }

        // register as not found to cache
        Cache::set($this->discogsEntry['master_id'], "not_found");
    }

    /**
     * @param array $trackUris
     * @return void
     */
    private function addTracksToPlaylist(array $trackUris): void
    {


        // remove any tracks which are already in the playlist, no need to re-add them
        foreach ($trackUris as $key => $value) {
            $spotifyTrack = new SpotifyTrack([
                'track_uri' => $value,
                'type' => 'new',
                'synchronization_id' => $this->synchronization->id
            ]);

            $spotifyTrack->save();

            if (in_array($value, $this->synchronization->current_spotify_tracks_cache)) {
                unset($trackUris[$key]);
            }
        }

        $this->synchronization->save();

        if(count($trackUris) > 0) {
            // add track to playlist
            $this->api->addPlaylistTracks($this->playlist->spotify_identifier, $trackUris);
        }
    }
}
