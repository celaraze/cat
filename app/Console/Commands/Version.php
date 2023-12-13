<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Version extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查看 CAT 版本号';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('1.0.0');
    }
}
