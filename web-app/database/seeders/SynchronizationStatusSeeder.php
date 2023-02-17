<?php

namespace Database\Seeders;

use App\Models\AccountType;
use App\Models\PlaylistType;
use App\Models\SynchronizationStatus;
use Illuminate\Database\Seeder;

class SynchronizationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonString = file_get_contents(base_path('database/data/synchronization_status.json'));
        $data = json_decode($jsonString, true); // decode the JSON into an array

        foreach ($data as $entry) {
            $model = new SynchronizationStatus($entry);
            $model->save();
        }
    }
}
