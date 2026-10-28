#!/usr/bin/env bash
# Standalone localhost demo; no external database, mail provider or airline API.
set -euo pipefail
cd "$(dirname "$0")/.."
if [[ ! -f .env ]]; then
  cp .env.demo.example .env
elif ! grep -Eq '^APP_ENV=demo$' .env; then
  echo 'Existing .env is not demo. Refusing to overwrite or touch its DB.' >&2
  exit 1
fi

if ! command -v php >/dev/null 2>&1 || ! command -v composer >/dev/null 2>&1; then
  echo 'Install PHP 8.3+ (pdo_sqlite, mbstring, dom, gd) and Composer first.' >&2
  exit 1
fi
if ! grep -Eq '^DB_CONNECTION=sqlite
touch database/demo.sqlite
php artisan optimize:clear
if grep -Eq '^APP_KEY=$' .env; then php artisan key:generate --force; fi
php artisan migrate --force
php artisan db:seed --class=PresentationSeeder --force
echo 'FlyHigh demo ready at http://127.0.0.1:8000'
echo 'Demo users: athena, hephaestus, argus and traveler @flyhigh.test'
php artisan serve --host=127.0.0.1 --port=8000
 .env; then
  echo 'Demo requires a dedicated SQLite database; refusing non-SQLite configuration.' >&2
  exit 1
fi
if [[ ! -d vendor ]]; then composer install --no-interaction --prefer-dist; fi
touch database/demo.sqlite
php artisan optimize:clear
if grep -Eq '^APP_KEY=$' .env; then php artisan key:generate --force; fi
php artisan migrate --force
php artisan db:seed --class=DemoSeeder --force
echo 'FlyHigh demo ready at http://127.0.0.1:8000'
echo 'Demo users: athena, hephaestus, argus and traveler @flyhigh.test'
php artisan serve --host=127.0.0.1 --port=8000
