from pydantic import BaseModel


class ChatWithText(BaseModel):
    text: str
