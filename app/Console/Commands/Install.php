<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'cat:install';

    protected $description = '应用安装脚本';

    public function handle(): void
    {
        $this->info('欢迎使用 CAT 一站式运维平台');
        $this->call('optimize:clear');
        $this->warn('正在设置存储系统');
        $this->call('storage:link');
        $this->warn('正在配置APP密钥');
        $this->call('key:generate');
        $this->call('migrate', ['--force']);
        $this->warn('正在初始化基础数据');
        $this->call('db:seed');
        $this->warn('请创建超级管理员账户');
        $this->call('make:filament-user', ['--name' => 'admin', '--email' => 'admin@localhost.com', '--password' => 'admin']);
        $this->warn('正在同步刷新权限，请耐心等待');
        $this->call('shield:generate', ['--all' => null]);
        $this->call('shield:super-admin');
        $this->call('cat:toc');
        $this->warn('安装完成！记得给作者点赞：✨ https://github.com/celaraze/cat.git ✨');
    }
}
