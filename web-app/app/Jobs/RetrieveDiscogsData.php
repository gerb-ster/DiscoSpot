<?php

namespace App\Jobs;

use App\Models\PlaylistType;
use App\Models\Statistic;
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

        switch ($synchronization->playlist->playlist_type_id) {
            case PlaylistType::BASED_ON_FOLDER:
                $folderId = $synchronization->playlist->discogs_query_data['folder_id'];
                $folder = "users/{$synchronization->playlist->owner->discogs_username}/collection/folders/{$folderId}";

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
                break;

            case PlaylistType::BASED_ON_LIST:
                $list = $discogsApi->get("lists/{$synchronization->playlist->discogs_query_data['list_id']}");

                $resourceUrls = [];

                // store some statistics
                $this->storeStatistic(Statistic::RELEASES_IN_LIST, count($list['items']));

                foreach ($list['items'] as $item) {
                    if ($item['type'] === "master") {
                        $resourceUrls[] = $item['resource_url'];
                    }
                }

                $this->batch()->add(new RetrieveDiscogsListContent(
                    $this->synchronizationUuid,
                    $resourceUrls
                ));

                break;

            case PlaylistType::BASED_ON_WANTLIST:
                $wantListResource = "users/{$synchronization->playlist->owner->discogs_username}/wants";
                $metadata = $discogsApi->get($wantListResource);

                // store some statistics
                $this->storeStatistic(Statistic::RELEASES_IN_WANTLIST, $metadata['pagination']['items']);

                // hydrate the batch with jobs
                for ($x = 1; $x <= ceil($metadata['pagination']['items'] / 100); $x++) {
                    $this->batch()->add(new RetrieveDiscogsWantListContent(
                        $this->synchronizationUuid,
                        $wantListResource,
                        $x
                    ));
                }

                break;

            default:
                throw new Exception('Unknown PlaylistTypeID');
        }
    }
}
