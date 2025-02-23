from memoryscope import MemoryScope, Arguments

from app.utils import config


def chat_and_remember(words: str):
    es_url = config.get_config()["memory_store"]["es_url"]
    arguments = Arguments(
        language="cn",
        human_name="user",
        assistant_name="assistant",
        memory_chat_class="api_memory_chat",
        generation_backend="dashscope_generation",
        generation_model="qwen-max",
        embedding_backend="dashscope_embedding",
        embedding_model="text-embedding-v2",
        rank_backend="dashscope_rank",
        rank_model="gte-rerank",
        enable_ranker=True,
        es_url=es_url,
    )
    config_path = config.get_config_path()
    with MemoryScope(config_path=config_path, arguments=arguments) as ms:
        memory_chat = ms.default_memory_chat
        response = memory_chat.chat_with_memory(query=words)
        if response is None:
            return None, None
        memory_service = ms.default_memory_service
        memory_service.init_service()
        memory = memory_service.consolidate_memory()
        print(f"新的记忆：{memory}")
        print(f"回复：{response.message.content}")
        return response.message.content, memory
