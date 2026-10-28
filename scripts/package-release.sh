#!/usr/bin/env bash
# Build a reproducible SOURCE archive from the current clean Git checkout.
set -euo pipefail
cd "$(dirname "$0")/.."
if [[ -n "$(git status --porcelain)" ]]; then
  echo "Worktree is not clean. Commit or stash changes before packaging." >&2
  exit 1
fi
if ! command -v zip >/dev/null 2>&1 || ! command -v sha256sum >/dev/null 2>&1; then
  echo "zip and sha256sum are required." >&2
  exit 1
fi
version=2.0.0
mkdir -p .releases
filename="flyhigh-v${version}-source.zip"
git archive --format=zip --prefix="flyhigh-v${version}/" --output=".releases/$filename" HEAD
(
  cd .releases
  sha256sum "$filename" > "$filename.sha256"
)
echo "Created .releases/$filename and checksum."
echo "Check the third-party media licenses and full archive before commercial distribution."
