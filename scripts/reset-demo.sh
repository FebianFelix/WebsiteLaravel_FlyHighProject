#!/usr/bin/env bash
# Resets only the named, isolated presentation Docker Compose project.
set -euo pipefail
cd "$(dirname "$0")/.."
if [[ ! -f docker-compose.demo.yml ]]; then
  echo "Demo compose file missing; run from the demo branch." >&2; exit 1
fi
if [[ "${1:-}" != "--yes" ]]; then
  echo "This removes ALL FlyHigh presentation data (fictional bookings, accounts, sessions)."
  read -r -p "Type RESET-DEMO to continue: " answer
  if [[ "$answer" != "RESET-DEMO" ]]; then echo "Cancelled."; exit 1; fi
fi
docker compose -f docker-compose.demo.yml down --volumes --remove-orphans
docker compose -f docker-compose.demo.yml up --build -d --wait
echo "Demo reset at http://127.0.0.1:${FLYHIGH_DEMO_PORT:-8088}"
