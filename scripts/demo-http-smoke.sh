#!/usr/bin/env bash
# Browser-level smoke: verifies boot, HTML, authentication and role-specific portal.
set -euo pipefail
url="${1:-http://127.0.0.1:8088}"
tmp="$(mktemp -d)"
trap 'rm -rf "$tmp"' EXIT
for i in $(seq 1 45); do
  if curl -fsS "$url/healthz" > /dev/null 2>&1; then break; fi
  sleep 2
done
curl -fsS "$url/" > "$tmp/home"
curl -fsS "$url/flights" > "$tmp/flights"
curl -fsS "$url/help" > "$tmp/help"
curl -fsS "$url/login" -c "$tmp/cookies" -b "$tmp/cookies" > "$tmp/login"
grep -qi 'flyhigh' "$tmp/home"
grep -q 'DEMO MODE' "$tmp/home"
grep -q 'DEMO201' "$tmp/flights"
grep -q 'How can we help' "$tmp/help"
grep -q 'athena@flyhigh.test' "$tmp/login"
token="$(sed -n 's/.*name="_token" value="\([^"]*\)".*/\1/p' "$tmp/login" | head -n1)"
test -n "$token"
password="${FLYHIGH_DEMO_PASSWORD:-DemoPass123!}"
curl -fsS -c "$tmp/cookies" -b "$tmp/cookies" \
  -d "_token=$token" --data-urlencode 'email=athena@flyhigh.test' \
  --data-urlencode "password=$password" \
  "$url/login" -D "$tmp/headers" -o /dev/null
grep -qE 'HTTP/[0-9.]+ 302' "$tmp/headers"
curl -fsS -b "$tmp/cookies" "$url/admin" > "$tmp/portal"
grep -q 'Configuration & management' "$tmp/portal"
grep -q 'Dashboard & monitoring' "$tmp/portal"
echo "PASS: demo HTTP, flight catalog, help, login and Athena portal."
