# 塞巴斯蒂安

[English](README.zh_CN.md) | 简体中文

一个拥有永久记忆的大模型助手。想象一下，除了它能提供大模型的基本问答外，它能够记住你提起过的任何事情，并且在任何时间回忆起来有多么酷！
中文对话基于 Qwen2-Max 大模型，英文对话基于 GPT-4o 大模型，使用 RAG 以及 embedding。并非通过携带上下文 tokens 实现。

和传统大模型对话助手相比，最直观的感受就是脱离了对话窗体的束缚，以往需要针对某个话题做单独对话，而现在它只有一个入口。
无论你和助手经历了多少次的对话，期间产生了庞大的信息量，它都能记住最久远的信息。

这可能是一个真正的“贾维斯”，私有化部署，无身份识别，数据安全。如此之外，它还有以下有点：

- 永久性的记忆能力。突破大模型上下文限制，不再出现“鱼的七秒记忆”。

- 记忆自动发掘。无需手动标记处理，交谈时助手将会自己发掘重要信息并记忆。

- 记忆迭代。助手会自动对已改变的重要信息做记忆更新，例如以前你可能不喜欢吃某个食物，但是现在喜欢了，助手会知悉并记住这个变化。

- 记忆关联。例如助手记住了你的生日，而后你告知你不喜欢自己的星座，根据关联计算出你的星座，助手将一并记忆你不喜欢这个星座。

- 巩固记忆。助手会周期性的对已经记住的重要信息做巩固，温故而知新，这将会提升下次回答时的反应速度。

关于数据安全性，我们提供了私有化部署的方案，你可以在自己的服务器上部署这个应用，所有的对话数据都不会离开你的服务器。
虽然我们目前使用了在线大模型推理服务，但这些服务商自身也提供了数据安全的保障。
为了考虑到用户的隐私洁癖，后续我们会推出针对本地 Ollama 解决方案的部署模式。

## 场景演示

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

### 部署

我们推荐使用 Docker Compose 进行部署，这会让整个安装和维护过程变得非常简单。

首先，确保你的计算机上已经安装了 Docker 以及 Docker Compose 组件，具体操作方式参考 Docker 官方网站。

即便现在本地部署大模型非常容易，但绝大多数用户无法承担起高性能大模型的成本，因此我们推荐使用云端大模型进行推理。

我们目前仅支持 Qwen2-Max 以及 GPT-4o 模型，后续会逐步推出基于 Ollama 的本地化部署。

第一步，如果使用中文模型对话，从 https://help.aliyun.com/zh/model-studio/developer-reference/get-api-key 获取 API KEY。
如果使用英文模型对话，则从 OpenAI 获取 API KEY。

第二步，编辑项目目录中 `docker-compose.yml`：

```yaml
# 对话模型语言，可选项：cn、en，默认为 cn。
- LANGUAGE=cn
# ··· 尤其注意以下两个参数必须二者选其一。
# 这是从第一步中获取的 API KEY，填写至此。
# 使用中文模型时填写此项。
- DASHSCOPE_API_KEY=
# 使用英文模型时填写此项。
- OPENAI_API_KEY=
# 这是由你决定的个人密钥，用于保护你的数据，后续接口访问的鉴权基于此。
- TOKEN=
# ···
```

第三步，执行 `docker compose up -d` 启动整个应用栈。

至此安装完毕。

### 调用接口

将下列 cUrl 范例中 `--header 'Authorization: Bearer test'` 的 `test` 替换为上述自行设定的 TOKEN。

文字对话：

```shell
curl --location --request POST 'http://127.0.0.1:8000/chat/text' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer test' \
--data-raw '{
    "text": "你好，我是张三，我出生于 2000 年 1 月 16 日。"
}'
```

语音对话：

```shell
curl --location --request POST 'http://127.0.0.1:8000/chat/audio' \
--header 'Authorization: Bearer test' \
--form 'file=@"C:\\Users\\test_user\\Downloads\\test.wav"'
```

响应体：

`response` 是大模型正常对话返回的回答内容。

`memory` 是此次对话中，大模型挖掘到的记忆信息变更。

```json
{
  "response": "您好！张三，有什么需要我协助的吗？",
  "memory": "新的记忆：用户的姓名是张三，用户出生于 2000 年 1 月 16 日。"
}
```

## 贡献

如果您有任何建议或发现任何错误，请随时提交问题或拉取请求。

## 赞助

`Afdian.net` 是一个为创作者提供支持的平台。如果你喜欢这个项目，可以在 `Afdian.net` 上支持我。

[https://afdian.net/a/celaraze](https://afdian.net/a/celaraze)

## 鸣谢

感谢 Gitee AI 模力方舟提供大模型推理能力。

<a href="#" target="_blank">
    <img src="https://img.picui.cn/free/2025/02/24/67bc321fc8383.png" width="200" alt="GiteeAI" />
</a>

感谢阿里云提供百炼大模型训练平台。

<a href="#" target="_blank">
    <img src="https://img.picui.cn/free/2025/02/24/67bc31c9574ec.png" width="200" alt="Aliyun" />
</a>

感谢 JetBrains 提供优秀的 IDE。

<a href="https://www.jetbrains.com/?from=cela" target="_blank">
    <img src="https://www.jetbrains.com/company/brand/img/jetbrains_logo.png" width="200" alt="JetBrains" />
</a>