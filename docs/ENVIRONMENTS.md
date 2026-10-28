# FlyHigh environments, branch policy, and CI/CD

The repository keeps **two isolated runtime environments** and exactly two long-lived branches:

| Concern | Development | Production |
|---|---|---|
| Git branch | `dev` | `master` |
| GitHub environment | `development` | `production` |
| App setting | `APP_ENV=development` | `APP_ENV=production` |
| Debug mode | `false` on internet-facing hosts | `false` |
| Example APP_URL | `https://dev.example.com` | `https://flyhigh.example.com` |
| Compose project | `flyhigh-development` | `flyhigh-production` |
| Local reverse-proxy port | `8081` | `8080` |
| Database | `flyhigh_development` | `flyhigh_production` |
| Secret keys, MySQL credentials, storage | **Different** | **Different** |
| Demo payment | Enabled for internal test | Disabled by default |
| Rollout | Automatic when configured | Requires configured approval gate |

**Important:** The two Docker deployment stacks isolate containers, volumes, PHP sessions and database credentials. For strong operational isolation, provision **different hosts or VMs**, distinct MySQL instances or managed databases, and separate SSH service accounts. Docker projects alone do not provide a hard trust boundary on one host.

## Flow

```text
feature/* -> Pull Request -> dev -> SQLite + MySQL tests
                                   -> GHCR image :sha-<commit>
                                   -> development (if enabled)
                                          |
                               validation / QA / sign-off
                                          |
                         Pull Request dev -> master
                                          |
                          production-policy check + CI
                                          |
                        GHCR image :sha-<commit>
                                          |
                        production environment approval
                                          |
                                  production (if enabled)
```

No deployment occurs on a pull request. Every build is tagged with the source commit and each deployment uses the **image's immutable SHA-256 registry digest**, not a mutable `latest` tag. Dev and production use different deployments, secrets, and artifacts even when the application code is identical.

## Already committed to Git

- `.github/workflows/tests.yml`: SQLite + MySQL tests, image build/push, conditional CD.
- `phpunit.mysql.xml`: integration test configuration for ephemeral MySQL CI.
- `docker/compose.deploy.yml`: reusable production-style runtime (app, scheduler, nginx, assets).
- `docker/nginx.deploy.conf`: Nginx proxy in each stack.
- `docker/deploy-entrypoint.sh`: runtime migration, cache build, service launch, readiness check.
- `scripts/cd-deploy.sh`: OpenSSH deploy transport with pinned known-hosts checking.
- `deploy/examples/`: sanitized app config examples for both runtime environments.

### GitHub configuration — requires repository administrator

The connected GitHub integration cannot currently create/protect GitHub Environments or edit their secrets. Configure these settings in the repository UI before enabling deployment.

1. In **Settings → Environments**, create **`development`** and **`production`**.
2. For `production`, enable **Required reviewers**, choose authorized approvers, and restrict **Deployment branches** to `master`. For `development`, restrict to `dev`.
3. For **each environment**, create distinct environment secrets:
   - `DEPLOY_HOST` — hostname or fixed IP of that environment's server.
   - `DEPLOY_USER` — dedicated SSH service account.
   - `DEPLOY_SSH_KEY` — SSH private key, restricted to that environment.
   - `DEPLOY_KNOWN_HOSTS` — pinned, independently verified SSH host key line, NOT an unverified `ssh-keyscan` collected during deploy.
4. For **each environment**, set variables:
   - `DEPLOY_DIR` — e.g. `/opt/flyhigh/development` or `/opt/flyhigh/production`.
   - `DEPLOY_PROJECT` — `flyhigh-development` or `flyhigh-production`.
   - `DEPLOY_HTTP_PORT` — `8081` (development) or `8080` (production).
   - `DEPLOY_SSH_PORT` — usually `22`.
   - `DEPLOY_URL` — the public HTTPS URL for the environment.
5. In **Settings → Secrets and variables → Actions → Variables**, create repository-scoped flags:
   - `DEPLOY_DEVELOPMENT_ENABLED=true` to activate auto deployment on pushes to `dev`.
   - `DEPLOY_PRODUCTION_ENABLED=true` **only after** required-reviewer approval is enabled and production is verified.
   By default (flags missing or false), builds and tests run but deployments are **skipped safely**.
6. In **Settings → Branches / Rulesets**, protect both branches:
   - Disable force pushes and deletion for `master`.
   - Require PRs, reviews, resolved conversations, and passed checks (SQLite, MySQL and promotion policy where applicable) for `master`.
   - Require CI before merging into `dev`. Prefer no direct pushes once setup is done.
   - Allow `master` PRs **only from `dev`**; CI's promotion-policy job rejects other sources. Set this check as required in rulesets.
7. GitHub Container Registry permissions: the Actions `GITHUB_TOKEN` must have packages write for build/push. Deploy hosts must be able to pull the image, either because the GHCR package is made public or with a server-managed read-only GHCR token and `docker login ghcr.io`.

If required reviewers are unavailable under the repository's GitHub plan/settings, **leave production auto-CD disabled**; use a controlled manual deployment process until an equivalent approval gate is configured. Merely naming a GitHub Environment does not make it protected.

### Provision development server

Use a dedicated Linux VM, Docker Engine/Compose plugin, and a distinct deployment user with necessary Docker permissions. Set up HTTPS termination in a reverse proxy to **127.0.0.1:8081** and ensure the firewall does not expose this internal HTTP port. Provision a dedicated development MySQL database; store DB credentials and APP_KEY in:

```text
/opt/flyhigh/development/.env
```

Start from `deploy/examples/development.env.example`, but generate a unique `APP_KEY` and replace placeholders using server-side secret management. Set ownership/permissions to restrict the file to the deployment service account. A local `php artisan key:generate --show` can generate a new key without writing it to Git.

### Provision production server

Use **a separate Linux VM/server and database**, a different SSH key and `APP_KEY`. Set `APP_ENV=production`, `APP_DEBUG=false`, secure HTTPS cookies, a production mail provider, and separate DB credentials:

```text
/opt/flyhigh/production/.env
```

Start from `deploy/examples/production.env.example`. The application is **still a simulator** and cannot sell real airline tickets or accept real payments. Configure trusted TLS/reverse proxy so the production public domain forwards to **127.0.0.1:8080**. Make sure PHP sees its container environment through the supplied PHP-FPM config. Production data must never be copied from dev or seeded with arbitrary mock data.

### Deploy behavior

After tests pass, CI publishes `ghcr.io/<owner>/<repo>:sha-<commit>` and captures the pushed image digest `ghcr.io/<owner>/<repo>@sha256:<digest>`, which the deployment actually pulls. The selected environment job transfers *deployment configuration only*, never the app's `.env` or SSH private key. The remote server:

1. Validates the exact `APP_ENV` and (in production) `APP_DEBUG=false`.
2. Pulls the commit-tagged container image; refreshes version-matched static assets.
3. Starts PHP-FPM and the scheduler; runs `php artisan migrate --force`.
4. Builds Laravel caches; starts Nginx.
5. Performs localhost readiness checks, failing the job if unavailable.

**Deployments are not zero-downtime** and database migrations are not automatically reversible. Use backward-compatible migrations, a backup, maintenance planning, and reviewed release procedures for real data. No migrations or deploys should touch a production database without prior backups.

### Operations & rollback

- Review the previously deployed SHA-256 image digest and its database compatibility.
- Use an older image through the same server deployment procedure for an application rollback. Migrations need independent forward-fix strategy; do not run `migrate:rollback` blindly.
- Always rotate any historical leaked secret; `.env` was committed in the original university project, and removing the file from HEAD **does not** erase it from Git history.
- Configure monitoring, disaster-recovery testing, immutable image retention, backups and rate limits at the reverse proxy. Production readiness still requires MySQL concurrency load tests and security review.

## Manual development preview (without CD)

Local `docker-compose.yml` remains a developer-only sandbox; use `cp .env.example .env`, adjust local DB credentials and run `docker compose up --build`. It must never be pointed at production secrets.

For architecture and endpoint contracts, see [API.md](API.md) and [README.md](../README.md).
