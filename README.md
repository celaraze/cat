# 塞巴斯蒂安

简体中文 | [English](README/README.en_US.md)

一个拥有永久记忆的大模型助手。想象一下，除了它能提供大模型的基本问答外，它能够记住你提起过的任何事情，并且在任何时间回忆起来有多么酷！

这是一个真正的贾维斯，私有化部署，无身份识别，数据安全。如此之外，它还有以下有点：

1，突破大模型上下文限制，不再出现“鱼的七秒记忆”。

2，记忆自动发掘，无需手动标记，交谈间助手将会自己发掘并记忆。

3，记忆迭代，助手会自动更新记忆，以适应新的对话，例如你的名字，喜欢的颜色。

4：记忆关联，例如助手记住了你的生日，而后你告知你不喜欢自己的星座，根据关联计算出你的星座是X，助手将一并记忆你不喜欢X。

基于 Qwen2-Max 大模型，使用 RAG 以及 embedding。并非通过携带上下文 tokens 实现，每一次对话都是一个 token。

场景1：

```shell
# 事件发生于 2022年某天
用户：我今天刚买了一个黑色的鼠标，它有三年的退换服务。
助手：我记住了。
# 事件发生于 2025年某天
用户：我的鼠标的按键有些使用故障，你知道如何处理吗？
助手：您在 2022 年购买过一个黑色鼠标，现在即将到期，您可以联系售后服务。
用户：我忘记了，什么鼠标？
助手：一个黑色的鼠标，购买时间是 2012年X月X日。
```

场景2：

```shell
# 事件发生于某天。
用户：我是张三。
助手：你好，张三。
# 事件发生于几年后的某天，期间与助手经历过无数次对话，且助手应用时常关闭打开。
用户：我是谁？
助手：你是张三。
用户：我改名了，现在叫李四。
助手：好的，你的名字是李四。
```

## 快速开始

这是一个纯服务端应用程序，推荐使用 Docker 环境部署，具体参考
[使用手册](docs/使用手册.md)。

## 贡献

如果您有任何建议或发现任何错误，请随时提交问题或拉取请求。

## 赞助

`Afdian.net` 是一个为创作者提供支持的平台。如果你喜欢这个项目，可以在 `Afdian.net` 上支持我。

[https://afdian.net/a/celaraze](https://afdian.net/a/celaraze)

## 鸣谢

感谢 Gitee AI 模力方舟提供大模型推理能力。

<a href="#" target="_blank">
    <img src="http://oss.celaraze.com:9999/projects/sebastian/badges/badge-inspired-cn-black.svg" width="200" alt="GiteeAI" />
</a>
<a href="#" target="_blank">
    <img src="http://oss.celaraze.com:9999/projects/sebastian/badges/badge-powered-en-black.svg" width="200" alt="GiteeAI" />
</a>

感谢阿里云提供百炼大模型训练平台。

<a href="#" target="_blank">
    <img src="http://oss.celaraze.com:9999/projects/sebastian/badges/aliyun-bailian.png" width="200" alt="Aliyun" />
</a>

感谢 JetBrains 提供优秀的 IDE。

<a href="https://www.jetbrains.com/?from=cela" target="_blank">
    <img src="https://www.jetbrains.com/company/brand/img/jetbrains_logo.png" width="200" alt="JetBrains" />
</a>