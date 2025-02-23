from fastapi.testclient import TestClient

from app.main import app
from app.services import chat
from tests import functions

client = TestClient(app)