from memoryscope import MemoryScope, Arguments

from sebastian.utils import config


def chat_and_remember_by_chinese(words: str):
    es_url = config.get_config()["memory_store"]["es_url"]
    arguments = Arguments(
        language="cn",
        human_name="用户",
        assistant_name="助手",
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
        print(f"记忆：{memory}")
        print(f"回复：{response.message.content}")
        return response.message.content, memory


def chat_and_remember_by_english(words: str):
    es_url = config.get_config()["memory_store"]["es_url"]
    arguments = Arguments(
        language="en",
        human_name="User",
        assistant_name="Assistant",
        memory_chat_class="api_memory_chat",
        generation_backend="openai_generation",
        generation_model="gpt-4o",
        embedding_backend="openai_embedding",
        embedding_model="text-embedding-3-small",
        enable_ranker=False,
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
        print(f"memory：{memory}")
        print(f"Response：{response.message.content}")
        return response.message.content, memory
