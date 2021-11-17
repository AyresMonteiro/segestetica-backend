<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Project Docs';

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
        $scripts = [
            'Linux' =>
            'php ' . __DIR__ .
                '/../../../vendor/bin/openapi ' .
                '--output ' . __DIR__ . '/../../../public/swagger ' .
                __DIR__ . '/../../../routes/',
        ];

        $fallback = 'php -r "echo \"not implemented yet\n\";"';

        if (isset($scripts[PHP_OS])) {
            system($scripts[PHP_OS]);
            return 0;
        } else {
            system($fallback);
            return -1;
        }
    }
}
