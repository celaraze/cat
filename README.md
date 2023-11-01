# 项目说明

本项目是 `celaraze/chemex` 重构版。

原项目 `chemex` 名称弃用，重构版预计发布日期 2023 年底前。

需要老版本即 `chemex` IT
资产管理系统的，请访问：[https://github.com/celaraze/chemex.git](https://github.com/celaraze/chemex.git) 。

❤ 感谢各位支持。

<p align="center">
    <a href="https://pd.qq.com/s/sknbyfnh">用户交流频道</a>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Latest Release-WIP-orange" />
    <img src="https://img.shields.io/badge/PHP-8.1+-green" />
    <img src="https://img.shields.io/badge/MySQL-8+-blueviolet" />
    <img src="https://img.shields.io/badge/License-GPL3.0-blue" />
</p>

## 安装

> wissets 是个标准的 Laravel应用程序，也适用于所有的 LNMP/WNMP/DNMP 环境。

### 先决条件

git：用于管理版本，部署和升级必要工具。

PHP：仅支持 PHP8.1 以上版本。

composer：PHP 的包管理工具，用于安装必要的依赖包。

MySQL：数据库引擎，建议 MySQL 8 以上版本，理论上 MariaDB 10.11 以上版本兼容支持。

ext-zip：扩展。

ext-mysqli：扩展。

ext-xml：扩展。

ext-xlswriter：扩展，需要使用 pecl 命令安装，执行 `pecl install ext-xlswriter`。

以上扩展安装过程注意版本必须与 PHP 版本一致。

### 安装步骤

生产环境下为遵守安全策略，非常建议在服务器本地进行部署，暂时不提供相关线上初始化安装的功能。因此，虽然前期部署的步骤较多，但已经为大家自动化处理了很大部分的流程，只需要跟着下面的命令一步步执行，一般是不会有部署问题的。

1. 为你的计算机安装 `PHP8.1` 环境，参考：[PHP官方](https://www.php.net/downloads) 。

2. 为你的计算机安装 `MySQL` 或者 `mariadb`。

3. 在你想要的地方，执行 `mkdir chemex && cd chemex`。

4. 执行 `git clone https://github.com/celaraze/wissets.git .`，注意末尾的 `.` 也是需要包含的。

5. 执行 `cp .env.example .env`。

6. 根据 `.env` 文件中注释的指引进行配置。

7. 执行 `composer install` 安装依赖。

8. 你可能使用的web服务器为 `nginx` 以及 `apache`，无论怎样，应用的起始路径在 `/public` 目录，请确保指向正确，同时程序的根目录权限应该调整为：拥有者和你的
   Web 服务器运行用户一致，例如 www
   用户，且根目录权限为 `755`。

   `/storage` 目录设置为 `755` 权限。

   `/public` 目录设置为 `755` 权限。

9. 修改web服务器的伪静态规则为：`try_files $uri $uri/ /index.php?$args;`。

10. 执行 `php artisan wissets:install`。

11. 此时可以通过访问 `http://your_domain` 来访问系统。

## 问题反馈

最好的反馈方式是在本代码仓库中提交 Issues，我们在空余时间检查并解决。

请随时牢记，这是一个开源项目，作者有自己的生活、自己的工作。

## 漏洞上报

请直接邮件作者，勿在 Issues 中提交与安全相关的问题以免被恶意利用。

## 开源协议 & 约束条款

`wissets` 遵循 GPL3.0 开源协议。

GPL3.0 协议受国家法律认可且保护，一切基于 `wissets` 进行二次修改、分发、开源必须注明原作者及修改内容。在此基础上，所有商业行为需取得作者 Celaraze 书面许可，在部署本软件后将视为自动同意上述内容，未经作者书面许可的商业行为，作者将保留追究权利。

员工为其隶属企业部署本系统的行为不属于上述商业行为。

## 鸣谢

### JetBrains

<a href="https://www.jetbrains.com/?from=Chemex" target="_blank">
<img src="https://lab.celaraze.com:9999/chemex/jetbrains.png" width="150"/>
</a>

为本项目提供优秀的 IDE 。

### Laravel & Filament

为本项目提供后台框架支持。
