<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonString = file_get_contents(base_path('database/data/account_types.json'));
        $data = json_decode($jsonString, true); // decode the JSON into an array

        foreach ($data as $entry) {
            $model = new AccountType($entry);
            $model->save();
        }
    }
}
