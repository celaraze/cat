from fastapi import APIRouter, File, Header, HTTPException, status

from sebastian.schemas import request_json
from sebastian.services import chat
from sebastian.utils import asr, auth

router = APIRouter(
    prefix="/chat",
    tags=["chat"],
    dependencies=[],
    responses={404: {"message": "Not found"}},
)


@router.post("/text")
async def chat_with_text(
        form_data: request_json.ChatWithText,
        language: str = "cn",
        authorization: str = Header(None),
):
    auth.auth(authorization)
    print(form_data.text)
    if language == 'cn':
        response, memory = chat.chat_and_remember_by_chinese(form_data.text)
    elif language == 'en':
        response, memory = chat.chat_and_remember_by_english(form_data.text)
    else:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Language required."
        )
    print(response)
    print(memory)
    return {
        "response": response,
        "memory": memory
    }


@router.post("/audio")
async def chat_with_audio(
        file: bytes = File(),
        language: str = "cn",
        authorization: str = Header(None),
):
    auth.auth(authorization)
    text = asr.transcribe_wav(file)
    print(text)
    if language == 'cn':
        response, memory = chat.chat_and_remember_by_chinese(text)
    elif language == 'en':
        response, memory = chat.chat_and_remember_by_english(text)
    else:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND,
            detail="Language required."
        )
    print(response)
    print(memory)
    return {
        "response": response,
        "memory": memory
    }
