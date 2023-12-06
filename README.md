<p align="center">
    <img src="https://p.ipic.vip/p1umck.png" width="250">
</p>

<p align="center">
<a href="http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=oSXcaCdY4u5iIEQj43J2GsDk_PygRR2G&authKey=atvXMk1ZoXRwuuNzMLY7852APIHfnBp3cA4fu7oFui7MWRSCrg2EafCAI%2B9akAPa&noverify=0&group_code=1016567640"><img src="https://img.shields.io/badge/QQ-CAT 用户交流群-brown" /></a>
    <a href="https://pd.qq.com/s/sknbyfnh"><img src="https://img.shields.io/badge/QQ-CAT 用户交流频道-orange" /></a>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Latest Release-WIP-orange" />
    <img src="https://img.shields.io/badge/PHP-8.2+-green" />
    <img src="https://img.shields.io/badge/MySQL-8+-blueviolet" />
    <img src="https://img.shields.io/badge/License-GPL3.0-blue" />
</p>

<p align="center">
    这不是正式版本，目前仍处于开发阶段，请酌情用于生产环境。
</p>

来一杯咖啡与茶，为 IT 运维从业者减轻管理负担，提升管理效率，从繁重无序的工作中解压出来，利用剩余时间多喝一杯休息一下。

这是一个专为 IT 运维从业者打造的一站式解决方案平台，包含资产管理、工单、工作流、仓储等功能模块。

❤ 感谢各位支持。CAT 提倡与各位使用者、开发者一起创建健康生态，让本项目变的更好，欢迎提供 PR 贡献。

<p align="center">
    <img src="https://s3.bmp.ovh/imgs/2023/12/01/aba4e96303691dcd.png" />
</p>

## 发行策略

CAT 以滚动更新形式发布，具体版本号以发布日期标定。

| 频率 | 内容           |
|----|--------------|
| 每日 | 安全更新，紧急漏洞修复。 |
| 每周 | BUG 修复。      |
| 每月 | 新功能、新特性迭代。   |

| 分支   | 说明                     |
|------|------------------------|
| main | 正式版本分支，稳定且可被用于生产环境。    |
| dev  | 开发版本分支，极不稳定，不应被用于生产环境。 |

### 快速开始

通过访问 [**文档 Wiki**](https://github.com/celaraze/cat/wiki) 来安装和查看更多信息。

生产环境下为遵守安全策略，非常建议在服务器本地进行部署，暂时不提供相关线上初始化安装的功能。因此，虽然前期部署的步骤较多，但已经为大家自动化处理了很大部分的流程，只需要跟着下面的命令一步步执行，一般是不会有部署问题的。

1. 为你的计算机安装 `PHP8.2` 环境，参考：[PHP官方](https://www.php.net/downloads) 。

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

没错，本软件是开源的，但也是作者的劳动成果，软件并不是一天形成，需要日积月累的时间和精力开销。捐赠是完全自愿的，金额自愿，也是对作者成果的认可。适当的捐赠会激励作者持续维护，保持生态健康。

### 捐赠后有什么体现吗？

捐赠本身是无偿的，是对作者的支持和认可的一种体现。但同时，会将您的名字登记在此用于展示，如果您愿意，也可以加上您的网站地址。另外，也可以添加作者常用联系方式交流或留言，作者会在闲暇之余回复。

### 捐赠渠道

通过支付宝付款，捐赠后请通过 `celaraze@qq.com` 联系作者。

<img height="300" src="https://p.ipic.vip/jcx3h0.png"/>

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
