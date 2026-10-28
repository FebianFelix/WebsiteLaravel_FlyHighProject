#!/usr/bin/env bash
# GitHub Actions runner -> SSH server. Does not print or upload .env secrets.
set -euo pipefail

for name in DEPLOY_HOST DEPLOY_USER DEPLOY_SSH_KEY DEPLOY_KNOWN_HOSTS \
            DEPLOY_DIR DEPLOY_PROJECT DEPLOY_HTTP_PORT FLYHIGH_IMAGE \
            FLYHIGH_EXPECTED_ENV; do
  if [[ -z "${!name:-}" ]]; then
    echo "Missing $name (configure GitHub environment secrets/vars)" >&2
    exit 1
  fi
done

if [[ ! "$DEPLOY_DIR" =~ ^/[A-Za-z0-9/_-]+$ ]]; then
  echo "DEPLOY_DIR must be an absolute path without shell metacharacters" >&2
  exit 1
fi
if [[ ! "$DEPLOY_PROJECT" =~ ^[a-z0-9][a-z0-9_-]*$ ]]; then
  echo "Invalid DEPLOY_PROJECT" >&2
  exit 1
fi
if [[ ! "$DEPLOY_HTTP_PORT" =~ ^[0-9]{2,5}$ ]]; then
  echo "Invalid DEPLOY_HTTP_PORT" >&2
  exit 1
fi
if [[ ! "$DEPLOY_HOST" =~ ^[A-Za-z0-9.:-]+$ ]] ||
   [[ ! "$DEPLOY_USER" =~ ^[a-z_][a-z0-9_-]*$ ]]; then
  echo "DEPLOY_HOST or DEPLOY_USER contains invalid characters" >&2
  exit 1
fi

ssh_port="${DEPLOY_SSH_PORT:-22}"
if [[ ! "$ssh_port" =~ ^[0-9]{1,5}$ ]]; then
  echo "Invalid SSH port" >&2
  exit 1
fi

tmp="$(mktemp -d)"
trap 'rm -rf "$tmp"' EXIT
chmod 700 "$tmp"
printf '%s\n' "$DEPLOY_SSH_KEY" > "$tmp/id_deploy"
printf '%s\n' "$DEPLOY_KNOWN_HOSTS" > "$tmp/known_hosts"
chmod 600 "$tmp/id_deploy" "$tmp/known_hosts"

ssh_opts=(-p "$ssh_port" -i "$tmp/id_deploy" \
  -o IdentitiesOnly=yes -o StrictHostKeyChecking=yes \
  -o UserKnownHostsFile="$tmp/known_hosts" -o BatchMode=yes)
ssh_target="${DEPLOY_USER}@${DEPLOY_HOST}"

# Ship only deployment scripts and compose config; never source code secrets.
tar -C docker -cf - compose.deploy.yml nginx.deploy.conf deploy-entrypoint.sh | \
  ssh "${ssh_opts[@]}" "$ssh_target" \
  "mkdir -p '$DEPLOY_DIR' && tar -xf - -C '$DEPLOY_DIR'"

ssh "${ssh_opts[@]}" "$ssh_target" \
  "cd '$DEPLOY_DIR' && FLYHIGH_IMAGE='$FLYHIGH_IMAGE' \
   FLYHIGH_PROJECT='$DEPLOY_PROJECT' FLYHIGH_HTTP_PORT='$DEPLOY_HTTP_PORT' \
   FLYHIGH_EXPECTED_ENV='$FLYHIGH_EXPECTED_ENV' bash ./deploy-entrypoint.sh"
