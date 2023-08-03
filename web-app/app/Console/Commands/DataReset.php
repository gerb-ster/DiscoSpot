<?php

namespace app\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DataReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Database & Structure';

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
     */
    public function handle(): int
    {
        // make sure we're not going to send any emails
        config(['mail.default' => 'array']);

        $doReset = false;

        if ($this->confirm('Do you wish to reset the database?', true)) {
            $doReset = true;
        }

        if ($doReset) {
            Artisan::call('db:wipe --force');
            $this->info("✔ Wiped Database");

            Artisan::call('migrate --force');
            $this->info("✔ Migrations run");

            Artisan::call('db:seed --force');
            $this->info("✔ Database Seeded");
        }

        $this->warn("\n\n🏁 All Done!");

        return 0;
    }
}
