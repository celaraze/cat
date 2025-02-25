# Sebastian

A LLM model assistant with permanent memory. Imagine how cool it would be if, in addition to providing basic Q&A
services like other LLM, it could remember everything you've ever mentioned and recall it at any time!

For Chinese conversations, it is based on the Qwen2-Max LLM, and for English conversations, it is based on the GPT-4o
large language model. It uses RAG (Retrieval-Augmented Generation) and embeddings, instead of relying on carrying
context tokens.

Compared with traditional LLM chat assistants, the most intuitive difference is that it breaks free from the constraints
of the chat window. In the past, you had to start a separate conversation for a specific topic, but now there is only
one entry point.

No matter how many conversations you have with the assistant or how much information is generated during that process,
it can remember the oldest information.

This could be a real "Jarvis". It supports private deployment, has no identity recognition, and ensures data security.
Besides, it has the following advantages:

- **Permanent memory**: It breaks through the context limitations of LLM, eliminating the so - called "seven - second
  memory of a fish".

- **Automatic memory discovery**: There is no need for manual tagging. During conversations, the assistant will
  automatically discover and remember important information.

- **Memory iteration**: The assistant will automatically update its memory of changed important information. For
  example, if you used to dislike a certain food but now you like it, the assistant will be aware of and remember this
  change.

- **Memory association**: For instance, if the assistant remembers your birthday and then you tell it that you don't
  like your star sign, it can calculate your star sign based on the associated information and also remember your
  dislike for it.

- **Memory consolidation**: The assistant will periodically consolidate the important information it has remembered.
  Reviewing old knowledge can help it respond more quickly the next time you ask a question.

Regarding data security, we offer a private deployment solution. You can deploy this application on your own server, and
all conversation data will remain on your server. Although we currently use online large language model inference
services, these service providers also offer data security guarantees. To meet the privacy concerns of users, we will
introduce a deployment mode for the local Ollama solution in the future.

## Scenario Demonstration

Scenario 1:

```shell
# An event occurred on a certain day in 2022
User: I just bought a black mouse today, and it has a three - year return and exchange service.
Assistant: I've remembered that.
# An event occurred on a certain day in 2025
User: The buttons on my mouse are having some issues. Do you know how to deal with it?
Assistant: You bought a black mouse in 2022, and the warranty is about to expire. You can contact the after - sales service.
User: I forgot. What mouse are you talking about?
Assistant: It's a black mouse, and the purchase date was X month X day, 2022.
```

Scenario 2:

```shell
# An event occurred on a certain day.
User: I'm Zhang San.
Assistant: Hello, Zhang San.
# An event occurred a few years later. During this period, there were countless conversations with the assistant, and the assistant application was frequently closed and reopened.
User: Who am I?
Assistant: You're Zhang San.
User: I've changed my name. Now I'm Li Si.
Assistant: Okay, your name is Li Si.
```

## Quick Start

### Deployment

We recommend using Docker Compose for deployment, as it simplifies the entire installation and maintenance process.
First, make sure that Docker and Docker Compose components are installed on your computer. For specific operation
methods, refer to the official Docker website.

Even though local deployment of LLM is relatively easy now, most users cannot afford the cost of high - performance LLM.
Therefore, we recommend using cloud - based LLM for inference.

Currently, we only support the Qwen2-Max and GPT-4o models. In the future, we will gradually introduce local deployments
based on Ollama.

- **Step 1**: If you want to use the Chinese model for conversation, obtain the API KEY
  from https://help.aliyun.com/zh/model-studio/developer-reference/get-api-key. If you want to use the English model for
  conversation, obtain the API KEY from OpenAI.

- **Step 2**: Edit the `docker-compose.yml` in the project directory:

```yaml
# The language of the conversation model. Options: cn, en. The default is cn.
- LANGUAGE=cn
# ··· Pay special attention that you must choose one of the following two parameters.
# This is the API KEY obtained in the first step. Fill it in here.
# Fill this item when using the Chinese model.
- DASHSCOPE_API_KEY=
# Fill this item when using the English model.
- OPENAI_API_KEY=
# This is a personal key determined by you to protect your data. Subsequent interface access authentication is based on this.
- TOKEN=
# ···
```

**Step 3**: Execute `docker compose up -d` to start the entire application stack. The installation is now complete.

### Invoking the Interface

Replace `test` in `--header 'Authorization: Bearer test'` in the following cUrl examples with the TOKEN you set above.

**Text conversation**:

```shell
curl --location --request POST 'http://127.0.0.1:8000/chat/text' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer test' \
--data-raw '{
    "text": "Hello, I'm Zhang San, and I was born on January 16, 2000."
}'
```

**Voice conversation**:

```shell
curl --location --request POST 'http://127.0.0.1:8000/chat/audio' \
--header 'Authorization: Bearer test' \
--form 'file=@"C:\\Users\\test_user\\Downloads\\test.wav"'
```

**Response body**:

`response` is the answer returned by the large language model during normal conversation.

`memory` is the change in memory information mined by the large language model during this conversation.

```json
{
  "response": "Hello! Zhang San. Is there anything I can assist you with?",
  "memory": "New memory: The user's name is Zhang San, and the user was born on January 16, 2000."
}
```

## Contribution

If you have any suggestions or find any errors, please feel free to submit issues or pull requests.

## Sponsorship

`Afdian.net` is a platform that supports creators. If you like this project, you can support me on `Afdian.net`.
[https://afdian.net/a/celaraze](https://afdian.net/a/celaraze)

## Acknowledgments

Thanks to Gitee AI for providing large language model inference capabilities.

<a href="#" target="_blank">
    <img src="https://img.picui.cn/free/2025/02/25/67bd14a37b576.png" width="200" alt="GiteeAI" />
</a>

Thanks to Alibaba Cloud for providing the Bailian large language model training platform.

<a href="#" target="_blank">
    <img src="https://img.picui.cn/free/2025/02/24/67bc31c9574ec.png" width="200" alt="Aliyun" />
</a>

Thanks to JetBrains for providing excellent IDEs.

<a href="https://www.jetbrains.com/?from=cela" target="_blank">
<img src="https://www.jetbrains.com/company/brand/img/jetbrains_logo.png" width="200" alt="JetBrains" />
</a>