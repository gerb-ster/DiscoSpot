<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\Folder;
use App\Models\Release;
use App\Service\DiscogsApiClient;
use App\Service\DiscogsApiException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JetBrains\PhpStorm\NoReturn;

class TestSyncCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sync_collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Sync Collection';

    /**
     * @var DiscogsApiClient
     */
    private DiscogsApiClient $discogs;

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @var array
     */
    private array $collectedReleaseIds = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception|GuzzleException
     */
    #[NoReturn] public function handle(): int
    {

        $this->discogs = new DiscogsApiClient();
        $this->discogs->cacheEnabled = false;

        $this->collection = Collection::updateOrCreate(['username' => 'gerbster'], []);

        $folders = $this->discogs->get('users/gerbster/collection/folders');

        foreach ($folders['folders'] as $folder)
        {
            // skip folder id 0, the 'All' folder
            if($folder['id'] == 0) {
                continue;
            }

            Folder::updateOrCreate(['id' => $folder['id']], $folder);

            $this->syncFolderContent($folder['id']);
        }

        // remove any folders which are removed at Discogs
        Folder::whereNotIn('id', array_column($folders['folders'], 'id'))->delete();

        // remove any release which are removed at Discogs
        Release::whereNotIn('instance_id', $this->collectedReleaseIds)->delete();

        return 0;
    }

    /**
     * @param int $folderId
     * @param int $page
     * @return void
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    private function syncFolderContent(int $folderId, int $page = 1): void
    {
        $folderContent = $this->discogs->get("users/gerbster/collection/folders/{$folderId}/releases", [
            'per_page' => 100,
            'page' => $page,
            'sort_by' => 'added',
            'sort_order' => 'asc'
        ]);

        foreach ($folderContent["releases"] as $release) {
            $release['folder_id'] = $folderId;

            $releaseModel = Release::updateOrCreate(['instance_id' => $release['instance_id']], $release);

            //$releaseModel->folder()->associate($releaseModel);

            $this->collection
                ->releases()
                ->associate($releaseModel);
        }

        // now save the collection
        $this->collection->save();

        $this->collectedReleaseIds = array_merge(
            $this->collectedReleaseIds, array_column(
                $folderContent['releases'], 'instance_id'
            )
        );

        // loop until we've got it all
        if (intval($folderContent["pagination"]["pages"]) > $page) {
            $this->syncFolderContent($folderId, ++$page);
        }
    }
}
