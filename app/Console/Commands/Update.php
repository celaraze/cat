<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Update extends Command
{
    protected $signature = 'cat:update';

    protected $description = '更新';

    public function handle(): void
    {
        $this->call('migrate');
        $this->call('shield:generate', ['--all' => null]);
    }
}
