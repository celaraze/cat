#!/bin/sh

if [ "$LANGUAGE" = "en" ]; then
  CONFIG_FILE="/service/config/config_en.yaml"
else
  CONFIG_FILE="/service/config/config_cn.yaml"
fi

if [ -f "$CONFIG_FILE" ]; then
  sed -i "s|es_url:.*|es_url: \"$ES_URL\"|g" "$CONFIG_FILE"
  sed -i "s|token:.*|token: \"$TOKEN\"|g" "$CONFIG_FILE"
else
  echo "Error: $CONFIG_FILE does not exist."
  exit 1
fi

exec "$@"