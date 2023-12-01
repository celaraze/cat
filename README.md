<p align="center">
    <img src="https://p.ipic.vip/p1umck.png" width="250">
</p>

<p align="center">
<a href="http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=oSXcaCdY4u5iIEQj43J2GsDk_PygRR2G&authKey=atvXMk1ZoXRwuuNzMLY7852APIHfnBp3cA4fu7oFui7MWRSCrg2EafCAI%2B9akAPa&noverify=0&group_code=1016567640"><img src="https://img.shields.io/badge/QQ-CAT 用户交流群-brown" /></a>
    <a href="https://pd.qq.com/s/sknbyfnh"><img src="https://img.shields.io/badge/QQ-CAT 用户交流频道-orange" /></a>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Latest Release-WIP-orange" />
    <img src="https://img.shields.io/badge/PHP-8.1+-green" />
    <img src="https://img.shields.io/badge/MySQL-8+-blueviolet" />
    <img src="https://img.shields.io/badge/License-GPL3.0-blue" />
</p>

***这不是正式版本，目前仍处于开发阶段，请酌情用于生产环境。***

<p align="center">
    <img src="https://s3.bmp.ovh/imgs/2023/12/01/aba4e96303691dcd.png" />
</p>

来一杯咖啡与茶，为 IT 运维从业者减轻管理负担，提升管理效率，从繁重无序的工作中解压出来，利用剩余时间多喝一杯休息一下。

这是一个专为 IT 运维从业者打造的一站式解决方案平台，包含资产管理、工单、工作流、仓储等功能模块。

本项目是 celaraze/chemex 重构版。原项目 chemex 名称弃用，需要老版本即 chemex IT
资产管理系统的，请访问：[https://github.com/celaraze/chemex.git](https://github.com/celaraze/chemex.git) 。

与 chemex 对比，CAT 有什么不同：

1，CAT 采用全新架构设计，大量提升使用体验的细节，及紧跟最新版本潮流。

2，CAT 大部分会还原 chemex 的基础功能，但部分设计可能由于实际业务需求将被弃用。

3，重做了数据导出、导入功能，现在将提供一个更加人性化的方式。

4，简化了部署需求。

5，增加各类资产编号自动生成规则。

❤ 感谢各位支持。CAT 提倡与各位使用者、开发者一起创建健康生态，让本项目变的更好，欢迎提供 PR 贡献。

## 安装

> CAT 是个标准的 Laravel应用程序，也适用于所有的 LNMP/WNMP/DNMP 环境。

### 发行策略

CAT 以滚动更新形式发布，具体版本号以发布日期标定。

| 频率 | 内容           |
|----|--------------|
| 每日 | 安全更新，紧急漏洞修复。 |
| 每周 | BUG 修复。      |
| 每月 | 新功能、新特性迭代。   |

| 分支   | 说明                                                 |
|------|----------------------------------------------------|
| main | 正式版本分支，稳定且可被用于生产环境。                                |
| test | 开发版本分支，领先于 main 分支的特性、功能和修复项，仅做验证测试，不稳定，不应被用于生产环境。 |
| dev  | 开发版本分支，极不稳定，不应被用于生产环境。                             |

### 先决条件

git：用于管理版本，部署和升级必要工具。

PHP：仅支持 PHP8.1 以上版本。

composer：PHP 的包管理工具，用于安装必要的依赖包。

MySQL：数据库引擎，建议 MySQL 8 以上版本，理论上 MariaDB 10.11 以上版本兼容支持。

ext-intl：扩展。

ext-zip：扩展。

ext-pdo：扩展。

ext-mysqli：扩展。

ext-xml：扩展。

以上扩展安装过程注意版本必须与 PHP 版本一致。

### 快速开始

生产环境下为遵守安全策略，非常建议在服务器本地进行部署，暂时不提供相关线上初始化安装的功能。因此，虽然前期部署的步骤较多，但已经为大家自动化处理了很大部分的流程，只需要跟着下面的命令一步步执行，一般是不会有部署问题的。

1. 为你的计算机安装 `PHP8.1` 环境，参考：[PHP官方](https://www.php.net/downloads) 。

2. 为你的计算机安装 `MySQL` 或者 `MariaDB`。

3. 在你想要的地方，执行 `mkdir cat && cd cat`。

4. 执行 `git clone https://github.com/celaraze/cat.git .`，注意末尾的 `.` 也是需要包含的。

5. 执行 `cp .env.example .env`。

6. 根据 `.env` 文件中注释的指引进行配置。

7. 执行 `composer install` 安装后端依赖。

8. 执行 `npm install` 安装前端依赖。

9. 执行 `npm run build` 编译前端依赖。

10. 执行 `php artisan cat:install` 根据提示创建管理员账户。

11. 此时可以通过访问 `http://127.0.0.1:8000` 来访问系统。

### 生产环境最佳实践

请参考 Laravel 官方建议之部署指南：[Laravel 部署](https://learnku.com/docs/laravel/10.x/deployment/14840)。

站点主目录应该为 `your_path/cat/public` 作为入口。

针对 Nginx 的配置，请务必遵循一下伪静态规则：

```nginx
    ···
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~* \.(jpg|jpeg|gif|png|webp|svg|woff|woff2|ttf|css|js|ico|xml)$ {
        try_files $uri /index.php?$query_string;
        access_log off;
        log_not_found off;
        expires 14d;
    }
    
    ···
```

## 问题反馈

最好的反馈方式是在本代码仓库中提交 Issues，我们在空余时间检查并解决。

请随时牢记，这是一个开源项目，作者有自己的生活、自己的工作。

## 漏洞上报

请直接邮件作者 [celaraze@qq.com](mailto:celaraze@qq.com)，勿在 Issues 中及其它公开社区提交与安全相关的问题以免被恶意利用。

## 捐赠

### 捐赠清单

| 捐赠人 | 金额 | 时间 | 寄语 |
|-----|----|----|----|
|     |    |    |    |

### 这不是开源软件吗，我为什么要捐赠？

没错，本软件是开源的，但也是作者的成果，软件并不是一天形成，日积月累的时间和精力开销也摧残着作者。捐赠是完全自愿的，金额自愿，也是对作者成果的认可。最后，适当的捐赠会激励作者持续维护，保持生态健康。

### 捐赠后有什么体现吗？

捐赠本身是无偿的，是对作者的支持和认可的一种体现。但同时，会将您的名字登记在此用于展示，如果您愿意，也可以加上您的网站地址。另外，也可以添加作者常用联系方式交流或留言，作者会在闲暇之余回复。

### 捐赠渠道

通过支付宝转账至 `celaraze@qq.com`，备注可填写您的寄语或者网站地址。

## 开源协议 & 约束条款

CAT 遵循 GPL3.0 开源协议，***且源代码 100% 公开***。

GPL3.0 协议受国家、国际法律认可且保护，一切基于 CAT 进行二次修改、分发、开源必须注明原作者及公开修改内容相关源码。

***在此基础上，禁止任何人以任何形式售卖本软件，禁止使用本软件进行违法行为***。

### 信息收集公开

出于保护本开源软件权益，在安装过程中软件将一次性 *合法* 收集用户所部署环境中公开的 IP
地址，且被妥善保管。IP 地址仅用于记录软件安装数量用于后续针对性体验改进。

若不同意本信息收集条件，请放弃使用本软件。 在部署本软件后将视为自动同意上述内容。

## 鸣谢

### JetBrains

<a href="https://www.jetbrains.com/?from=cat" target="_blank">
    <img src="https://p.ipic.vip/woxqnn.png" width="200" />
</a>

为本项目提供优秀的 IDE 。

### Laravel & Filament

为本项目提供后台框架支持。
