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
    protected $signature = 'generate:docs {--c|continuous} {--sleepTime=}';

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

    public function run_command()
    {
        $openapi_path = __DIR__ . '/../../../vendor/bin/openapi';
        $swagger_dir = __DIR__ . '/../../../public/swagger';

        $search_paths = [
            __DIR__ . '/../../../routes/docs/',
            __DIR__ . '/../../../app/Http/Controllers',
            __DIR__ . '/../../../app/Models',
        ];

        $scripts = [
            'Linux' => 'php ' . $openapi_path . ' ' . implode(' ', $search_paths) . ' --output ' . $swagger_dir,
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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        do {
            $sleepTime = $this->option('sleepTime') != null ? $this->option('sleepTime') : 2;

            $this->run_command();
            echo now() . "\tCompiled.\n";
            sleep($sleepTime);
        } while ($this->option('continuous'));
    }
}
