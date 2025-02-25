#!/bin/sh

CONFIG_FILE="/service/config/config.yaml"

if [ -f "$CONFIG_FILE" ]; then
  sed -i "s|es_url:.*|es_url: \"$ES_URL\"|g" "$CONFIG_FILE"
  sed -i "s|token:.*|token: \"$TOKEN\"|g" "$CONFIG_FILE"
else
  echo "Error: $CONFIG_FILE does not exist."
  exit 1
fi

exec "$@"