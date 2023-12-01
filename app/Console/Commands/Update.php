<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('mirage');
    }
}
