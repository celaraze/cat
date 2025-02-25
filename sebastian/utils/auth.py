from fastapi import HTTPException

from sebastian.utils import config


def auth(authorization: str):
    if authorization is None:
        raise HTTPException(status_code=401, detail="Authorization header missing")
    parts = authorization.split()
    if len(parts) != 2 or parts[0].lower() != "bearer":
        raise HTTPException(status_code=401, detail="Invalid Authorization header format")
    auth_token = parts[1]
    token = config.get_config()['auth']['token']
    if auth_token != token:
        raise HTTPException(status_code=401, detail="Authorization failed")
