import os

import yaml


def get_config():
    try:
        with open(get_config_path(), encoding="utf-8") as f:
            return yaml.safe_load(f)
    except Exception:
        raise


def get_config_path():
    # sebastian/app/utils
    current_file_path = os.path.dirname(os.path.abspath(__file__))
    # sebastian/app
    current_file_path = os.path.dirname(current_file_path)
    # sebastian
    current_file_path = os.path.dirname(current_file_path)
    return f"{current_file_path}/config/config.yaml"
