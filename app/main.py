from fastapi import FastAPI

from app.controllers import (
    chat_controller,
)

tags_metadata = [
    {
        "name": "auth",
        "description": "Operations with authentication.",
    },
]

description = """
`Sebastian` API helps you do awesome stuff. 🚀
"""

app = FastAPI(
    title="Sebastian",
    description=description,
    summary="Sebastian APIs",
    version="0.0.1",
    contact={
        "name": "celaraze",
        "url": "https://github.com/celarze",
        "email": "celaraze@qq.com",
    },
    license_info={
        "name": "MIT",
        "url": "https://opensource.org/licenses/MIT",
    },
    openapi_tags=tags_metadata,
)

app.include_router(chat_controller.router)


@app.get("/")
async def home():
    body = {
        "message": "Welcome to Sebastian API.",
        "data": {
            "version": "0.0.3-alpha",
            "author": "celaraze",
            "github": "https://github.com/celaraze/sebastian.git",
        }
    }
    return body
