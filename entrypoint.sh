#!/bin/sh

if [ ! -f /service/config/config.yml ]; then
  cat << EOF > /service/config/config.yml
memory_store:
  es_url: "$ES_URL"
EOF
fi

exec "$@"