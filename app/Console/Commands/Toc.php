<?php

namespace App\Console\Commands;

use App\Utils\LogUtil;
use Illuminate\Console\Command;
use Mockery\Exception;

class Toc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat:toc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            LogUtil::toc();
        } catch (Exception $exception) {

        }
    }
}
