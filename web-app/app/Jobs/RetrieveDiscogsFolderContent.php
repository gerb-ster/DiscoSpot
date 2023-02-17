<?php

namespace App\Jobs;

use App\Models\DiscogsRelease;
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

        $synchronization = Synchronization::firstWhere('uuid', $this->synchronizationUuid);

        $discogsApi = new DiscogsApiClient(
            $synchronization->playlist->owner->discogs_token,
            $synchronization->playlist->owner->discogs_secret
        );

        $folderContent = $discogsApi->get($this->resourceUrl, [
            'per_page' => 100,
            'page' => $this->page
        ]);

        foreach ($folderContent['releases'] as $entry) {
            // apply filters..
            if ($this->passesFilter($synchronization, $entry['basic_information'])) {
                $discogsRelease = new DiscogsRelease([
                    'artist' => current($entry['basic_information']['artists'])['name'],
                    'title' => $entry['basic_information']['title'],
                    'master_id' => $entry['basic_information']['title'],
                    'synchronization_uuid' => $this->synchronizationUuid
                ]);

                $discogsRelease->save();
            }
        }
    }

    /**
     * @param Synchronization $synchronization
     * @param array $basicInformation
     * @return bool
     */
    private function passesFilter(Synchronization $synchronization, array $basicInformation): bool
    {
        foreach ($synchronization->playlist->discogs_query_data['filters'] as $key => $value) {
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
