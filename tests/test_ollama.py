from fastapi.testclient import TestClient

from sebastian.main import app
from sebastian.services import chat
from tests import functions

client = TestClient(app)