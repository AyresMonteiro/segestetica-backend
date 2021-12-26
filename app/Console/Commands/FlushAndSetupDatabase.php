<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FlushAndSetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush and set up database.';

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
    public function handle()
    {
        // Flushing database.
        system("php artisan db:wipe");
        
        // Migrating again.
        system("php artisan migrate");

        // Seeding database.
        system("php artisan db:seed");
        
        return Command::SUCCESS;
    }
}
