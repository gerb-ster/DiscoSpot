<?php

namespace App\Jobs;

use App\Models\Statistic;
use App\Models\Synchronization;
use App\Service\DiscogsApiClient;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class RetrieveDiscogsData implements ShouldQueue
{
    use Synchronize, Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * @throws Exception
     * @throws Throwable
     */
    public function handle(): void
    {
        $synchronization = $this->getSynchronization();

        $discogsApi = new DiscogsApiClient(
            $synchronization->playlist->owner->discogs_token,
            $synchronization->playlist->owner->discogs_secret
        );

        $folder_id = $synchronization->playlist->discogs_query_data['folder_id'];
        $folder = "users/{$synchronization->playlist->owner->discogs_username}/collection/folders/{$folder_id}";

        $metadata = $discogsApi->get($folder);

        // store some statistics
        $this->storeStatistic(Statistic::RELEASES_IN_FOLDER, $metadata['count']);

        // hydrate the batch with jobs
        for ($x = 1; $x <= ceil($metadata['count'] / 100); $x++) {
            $this->batch()->add(new RetrieveDiscogsFolderContent(
                $this->synchronizationUuid,
                $folder . "/releases",
                $x
            ));
        }
    }
}
