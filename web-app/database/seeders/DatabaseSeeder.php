<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AccountTypeSeeder::class);
        $this->call(PlaylistTypeSeeder::class);
        $this->call(FilterTypeSeeder::class);
        $this->call(SynchronizationStatusSeeder::class);
    }
}
