<?php

namespace App\Console\Commands;

use App\Utils\LogUtil;
use Illuminate\Console\Command;
use Mockery\Exception;

class Toc extends Command
{
    protected $signature = 'cat:toc';

    protected $description = 'CAT TOC';

    public function handle(): void
    {
        try {
            LogUtil::toc();
        } catch (Exception $exception) {
            LogUtil::error($exception);
        }
    }
}
