<?php

namespace app\Jobs;

use App\Models\DiscogsRelease;
use App\Service\DiscogsApiClient;
use App\Service\DiscogsApiException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetrieveDiscogsWantListContent implements ShouldQueue
{
    use Synchronize, Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $synchronizationUuid;

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
    public function __construct(string $synchronizationUuid, string $resourceUrl, int $page)
    {
        $this->synchronizationUuid = $synchronizationUuid;

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

        $synchronization = $this->getSynchronization();

        $discogsApi = new DiscogsApiClient(
            $synchronization->playlist->owner->discogs_token,
            $synchronization->playlist->owner->discogs_secret
        );

        try {
            $folderContent = $discogsApi->get($this->resourceUrl, [
                'per_page' => 100,
                'page' => $this->page
            ]);

            ray($folderContent);

            foreach ($folderContent['wants'] as $entry) {
                // apply filters..
                if ($this->passesFilter($synchronization, $entry['basic_information'])) {
                    $discogsRelease = new DiscogsRelease([
                        'artist' => current($entry['basic_information']['artists'])['name'],
                        'title' => $entry['basic_information']['title'],
                        'master_id' => $entry['basic_information']['id'],
                        'synchronization_uuid' => $this->synchronizationUuid
                    ]);

                    $discogsRelease->save();
                }
            }
        } catch (DiscogsApiException|GuzzleException $e) {
            // log error
        }
    }
}
