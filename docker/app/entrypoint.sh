#!/usr/bin/env sh
set -eu

until pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USER}" -d "${DB_NAME}" >/dev/null 2>&1; do
  sleep 1
done

php /app/yii migrate --interactive=0

exec /usr/bin/supervisord -c /etc/supervisord.conf
