<?php

namespace app\Jobs;

use App\Models\DiscogsRelease;
use App\Models\Synchronization;
use App\Service\DiscogsApiClient;
use App\Service\DiscogsApiException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetrieveDiscogsListContent implements ShouldQueue
{
    use Synchronize, Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $synchronizationUuid;

    /**
     * @var array
     */
    private array $resourceUrls;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $synchronizationUuid, array $resourceUrls)
    {
        $this->synchronizationUuid = $synchronizationUuid;

        $this->resourceUrls = $resourceUrls;
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

        ray($this->resourceUrls);

        $discogsApi = new DiscogsApiClient(
            $synchronization->playlist->owner->discogs_token,
            $synchronization->playlist->owner->discogs_secret
        );

        foreach ($this->resourceUrls as $resourceUrl) {
            try {
                $release = $discogsApi->get($resourceUrl);

                // apply filters..
                if ($this->passesFilter($synchronization, $release)) {
                    $discogsRelease = new DiscogsRelease([
                        'artist' => current($release['artists'])['name'],
                        'title' => $release['title'],
                        'master_id' => $release['id'],
                        'synchronization_uuid' => $this->synchronizationUuid
                    ]);

                    $discogsRelease->save();
                }
            } catch (DiscogsApiException|GuzzleException $e) {
                ray($e);
            }
        }
    }
}
