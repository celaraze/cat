<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cat:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '应用安装脚本';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('欢迎使用 CAT 一站式信息运维平台');
        $this->call('optimize:clear');
        $this->warn('正在设置存储系统');
        $this->call('storage:link');
        $this->warn('正在配置APP密钥');
        $this->call('key:generate');
        $this->call('migrate');
        $this->warn('正在初始化基础数据');
        $this->call('db:seed');
        $this->warn('创建超级管理员账户');
        $this->call('make:filament-user');
        $this->call('cat:toc');
    }
}
