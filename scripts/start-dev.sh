#!/usr/bin/env bash
# Local dev bootstrap: isolated MySQL and PHP dev dependencies in Docker.
set -euo pipefail
cd "$(dirname "$0")/.."
if ! command -v docker >/dev/null 2>&1; then
  echo "Docker with Compose plugin is required" >&2; exit 1
fi
if ! docker compose version >/dev/null 2>&1; then
  echo "Docker Compose plugin is required" >&2; exit 1
fi
if ! command -v openssl >/dev/null 2>&1; then
  echo "OpenSSL is required for generating random LOCAL credentials" >&2; exit 1
fi

if [[ ! -f .env ]]; then
  cp .env.example .env
  password="$(openssl rand -hex 24)"
  root_password="$(openssl rand -hex 24)"
  app_key="base64:$(openssl rand -base64 32 | tr -d '\n')"
  sed -i.bak \
    -e 's/^APP_ENV=.*/APP_ENV=development/' \
    -e 's/^APP_DEBUG=.*/APP_DEBUG=false/' \
    -e 's#^APP_URL=.*#APP_URL=http://127.0.0.1:8080#' \
    -e 's/^DB_HOST=.*/DB_HOST=db/' \
    -e 's/^DB_DATABASE=.*/DB_DATABASE=flyhigh_development/' \
    -e 's/^DB_USERNAME=.*/DB_USERNAME=flyhigh_dev/' \
    -e "s/^DB_PASSWORD=.*/DB_PASSWORD=$password/" \
    -e "s#^APP_KEY=.*#APP_KEY=$app_key#" .env
  rm -f .env.bak
  printf 'MYSQL_ROOT_PASSWORD=%s\n' "$root_password" >> .env
  chmod 600 .env
fi
if ! grep -Eq '^APP_ENV=development$' .env; then
  echo "Refusing to use an existing non-development .env file." >&2; exit 1
fi
if ! grep -Eq '^DB_HOST=db$' .env || ! grep -Eq '^DB_CONNECTION=mysql$' .env; then
  echo "Dev Docker requires a separate MySQL service named db." >&2; exit 1
fi

docker compose up -d --build --wait
docker compose exec -T app php artisan flyhigh:doctor --check-db
docker compose exec -T app php artisan migrate --force
if [[ "${1:-}" == "--seed" ]]; then
  docker compose exec -T app php artisan db:seed --force
fi
echo "FlyHigh development: http://127.0.0.1:8080"
echo "Run tests: docker compose exec -T app php artisan test"
echo "Stop: docker compose down (without deleting MySQL volume)"
