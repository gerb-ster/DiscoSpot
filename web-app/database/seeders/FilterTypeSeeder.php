<?php

namespace database\seeders;

use App\Models\FilterType;
use Illuminate\Database\Seeder;

class FilterTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $jsonString = file_get_contents(base_path('database/data/filter_types.json'));
        $data = json_decode($jsonString, true); // decode the JSON into an array

        foreach ($data as $entry) {
            $model = new FilterType($entry);
            $model->save();
        }
    }
}
