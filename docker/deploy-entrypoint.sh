#!/usr/bin/env bash
# Validated deployment on a target host, with .env owned by that host.
set -euo pipefail

for name in FLYHIGH_IMAGE FLYHIGH_PROJECT FLYHIGH_HTTP_PORT FLYHIGH_EXPECTED_ENV; do
  if [[ -z "$(printenv "$name" 2>/dev/null || true)" ]]; then
    echo "Missing required runtime variable $name" >&2
    exit 1
  fi
done

if [[ "$FLYHIGH_EXPECTED_ENV" != "development" && "$FLYHIGH_EXPECTED_ENV" != "production" ]]; then
  echo "Refusing deployment outside development/production" >&2
  exit 1
fi
if [[ ! -f .env ]]; then
  echo "Server-managed .env is missing." >&2
  exit 1
fi
actual_env="$(grep -m1 '^APP_ENV=' .env | cut -d= -f2- | tr -d "'\"[:space:]" || true)"
if [[ "$actual_env" != "$FLYHIGH_EXPECTED_ENV" ]]; then
  echo "APP_ENV must match target environment ($FLYHIGH_EXPECTED_ENV)" >&2
  exit 1
fi
if [[ "$FLYHIGH_EXPECTED_ENV" == "production" ]]; then
  if ! grep -Eq '^APP_DEBUG=(false|0)$' .env; then
    echo "Production requires APP_DEBUG=false" >&2
    exit 1
  fi
  if grep -Eq '^FLYHIGH_DEMO_PAYMENTS=(true|1)$' .env; then
    echo "Production forbids demo payment settlement" >&2
    exit 1
  fi
fi
if [[ ! "$FLYHIGH_IMAGE" =~ ^ghcr\.io/.+@sha256:[0-9a-f]{64}$ ]]; then
  echo "Image must be pinned to immutable GHCR sha256 digest" >&2
  exit 1
fi

export FLYHIGH_IMAGE FLYHIGH_PROJECT FLYHIGH_HTTP_PORT
compose() { docker compose --env-file .env -f compose.deploy.yml "$@"; }

compose config --quiet
docker pull "$FLYHIGH_IMAGE"

# Validate the NEW image before replacing the running app/scheduler. The DB is
# probed on the same Docker network as the target deployment.
compose run --rm --no-deps app php artisan flyhigh:doctor --strict --check-db

# Deploy is not zero-downtime and migrations must remain backward-compatible.
compose run --rm assets
compose up -d --no-deps app scheduler
compose exec -T app php artisan migrate --force
compose exec -T app php artisan optimize
compose up -d --no-deps web

curl --fail --silent --show-error --retry 8 --retry-delay 2 \
  "http://127.0.0.1:$FLYHIGH_HTTP_PORT/healthz" >/dev/null

echo "FlyHigh $FLYHIGH_EXPECTED_ENV deployed from $FLYHIGH_IMAGE"
