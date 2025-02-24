from fastapi import APIRouter, File, Header

from app.schemas import request_json
from app.services import chat
from app.utils import asr, auth

router = APIRouter(
    prefix="/chat",
    tags=["chat"],
    dependencies=[],
    responses={404: {"message": "Not found"}},
)


@router.post("/text")
async def chat_with_text(
        form_data: request_json.ChatWithText,
        authorization: str = Header(None),
):
    auth.auth(authorization)
    print(form_data.text)
    response, memory = chat.chat_and_remember(form_data.text)
    return {
        "response": response,
        "memory": memory
    }


@router.post("/audio")
async def chat_with_audio(
        file: bytes = File(),
        authorization: str = Header(None),
):
    auth.auth(authorization)
    text = asr.transcribe_wav(file)
    print(text)
    response, memory = chat.chat_and_remember(text)
    return {
        "response": response,
        "memory": memory
    }
