#!/usr/bin/env bash
# Runs on the target server, never stores credentials in the repository.
set -euo pipefail

: "${FLYHIGH_IMAGE:?Missing immutable image}"
: "${FLYHIGH_PROJECT:?Missing compose project name}"
: "${FLYHIGH_HTTP_PORT:?Missing local HTTP port}"
: "${FLYHIGH_EXPECTED_ENV:?Missing expected environment}"

if [[ ! -f .env ]]; then
  echo "Missing server-managed .env file. Provision this environment first." >&2
  exit 1
fi

actual_env="$(grep -m1 '^APP_ENV=' .env | cut -d= -f2- | tr -d "'\"[:space:]" || true)"
if [[ "$actual_env" != "$FLYHIGH_EXPECTED_ENV" ]]; then
  echo "Refusing deployment: APP_ENV must be $FLYHIGH_EXPECTED_ENV; found $actual_env" >&2
  exit 1
fi

if [[ "$FLYHIGH_EXPECTED_ENV" == "production" ]] &&
   ! grep -Eq '^APP_DEBUG=(false|0)$' .env; then
  echo "Refusing production deployment: APP_DEBUG must be false" >&2
  exit 1
fi

if [[ ! "$FLYHIGH_IMAGE" =~ ^ghcr\.io/.+@sha256:[0-9a-f]{64}$ ]]; then
  echo "Refusing image without immutable sha256 digest" >&2
  exit 1
fi

export FLYHIGH_IMAGE FLYHIGH_PROJECT FLYHIGH_HTTP_PORT
compose=(docker compose --env-file .env -f compose.deploy.yml)

"${compose[@]}" config --quiet
docker pull "$FLYHIGH_IMAGE"
"${compose[@]}" run --rm assets
"${compose[@]}" up -d --no-deps app scheduler
"${compose[@]}" exec -T app php artisan migrate --force
"${compose[@]}" exec -T app php artisan optimize
"${compose[@]}" up -d --no-deps web

# The endpoint is bound to localhost only; external TLS is managed separately.
curl --fail --silent --show-error --retry 8 --retry-delay 2 \
  "http://127.0.0.1:${FLYHIGH_HTTP_PORT}/healthz" > /dev/null

echo "FlyHigh $FLYHIGH_EXPECTED_ENV deployment completed: $FLYHIGH_IMAGE"
