<?php

namespace App\Jobs;

use App\Models\Playlist;
use App\Models\Synchronization;
use App\Service\DiscogsApiClient;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetrieveDiscogsFolderContent implements ShouldQueue
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
     * @var string
     */
    private string $resourceUrl;

    /**
     * @var int
     */
    private int $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist, Synchronization $synchronization, string $resourceUrl, int $page)
    {
        $this->playlist = $playlist;
        $this->synchronization = $synchronization;

        $this->resourceUrl = $resourceUrl;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $discogsApi = new DiscogsApiClient(
            $this->playlist->owner->discogs_token,
            $this->playlist->owner->discogs_secret
        );

        $folderContent = $discogsApi->get($this->resourceUrl, [
            'per_page' => 100,
            'page' => $this->page
        ]);

        $discogsData = [];

        foreach ($folderContent['releases'] as $entry) {
            // apply filters..
            if ($this->passesFilter($entry['basic_information'])) {
                $discogsData[] = $entry['basic_information'];
            }
        }

        $this->synchronization->refresh();

        $this->synchronization->discogs_data = array_merge($this->synchronization->discogs_data, $discogsData);
        $this->synchronization->save();
    }

    /**
     * @param array $basicInformation
     * @return bool
     */
    private function passesFilter(array $basicInformation): bool
    {
        foreach ($this->playlist->discogs_query_data['filters'] as $key => $value) {
            if(array_key_exists($key, $basicInformation)) {
                foreach ($basicInformation[$key] as $entry) {
                    if($entry['name'] === $value) {
                        return true;
                    }
                }
            }
        }

        return false;
     }
}
