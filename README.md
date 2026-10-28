# FlyHigh — Business-Grade Laravel Booking Portfolio

> **© 2023–2026 FebianFelix · Source-available.** Original source:
> https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject
>
> FlyHigh is a working, demonstrative travel-booking platform. **No airline
> inventory, actual payments, or valid travel documents are issued.** It is
> not an OTA, ticket agency, or authorized airline reseller.

## Capabilities

| Domain | Implemented |
|---|---|
| Travel discovery | Filter origin, destination, date, airline, max price; sorting and pagination |
| Reservations | 1–6 travelers, unique reference, idempotent retry keys, time-limited seat holds |
| Concurrency | Database transaction + flight row locking (MySQL/PostgreSQL) |
| Payment lifecycle | Clearly labeled fake payment confirmation, no financial processing |
| Tickets | Restricted demo itinerary, signed QR verification, PDF export |
| Accounts | Registration, login, email verification, password reset, profile, API tokens |
| Admin | Role-protected flight CRUD, analytics, booking search and CSV export |
| REST API | Sanctum authenticated versioned booking endpoints, public flight search |
| Ops | Scheduler, Docker/Nginx/MySQL sample, GitHub Actions tests, security documentation |
| Provenance | LICENSE, NOTICE, backend config, DB provenance record, HTTP headers, footer |

## Development versus production CI/CD

- **`dev`** is the development branch. Passing commits build a GHCR image and can deploy automatically to the isolated `development` environment.
- **`master`** is the production branch. Promote code using a reviewed `dev → master` pull request. Protected `production` environment approvals are required before enabling live deployment.
- Both run SQLite and MySQL CI. No server deployments happen until the GitHub environment secrets and explicit repository flags are configured.

See the **[two-environment operations guide](docs/ENVIRONMENTS.md)** for server preparation, GitHub branch protection, secret separation, approval gates, image tags, deployment and recovery.

## Technology

Laravel 13, PHP 8.3+, Blade, MySQL 8.4 or SQLite, Laravel Sanctum,
Dompdf, endroid QR Code, automated PHPUnit tests, Docker Compose.

## Local install

```bash
git clone https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject.git
cd WebsiteLaravel_FlyHighProject
composer install
cp .env.example .env
php artisan key:generate
```

Use a **new** database and edit DB settings in `.env`. For SQLite, create
`database/database.sqlite` and configure its absolute path, or use MySQL.
Do NOT run migration against the old 2023 `tbl_*` schema without backup;
migration of that legacy data is not implemented.

```bash
php artisan migrate --seed
php artisan serve
```

Start Laravel scheduler in another terminal (required to expire pending holds):

```bash
php artisan schedule:work
```

Open http://localhost:8000, create an account and optionally promote an
admin **through the server CLI**:

```bash
php artisan flyhigh:make-admin you@example.com
```

The operation requires confirmation. No default administrator password
or seeded admin user is created.

`FLYHIGH_DEMO_PAYMENTS=true` enables the **demo-only** settlement button.
It never contacts a card processor, bank, or airline.

## Docker-based local environment

Create a separate `.env` with a fresh `APP_KEY`, a strong `DB_PASSWORD`,
`MYSQL_ROOT_PASSWORD`, and `APP_URL=http://localhost:8080`.
Then:

```bash
docker compose up --build -d
docker compose exec app php artisan migrate --seed
```

Open http://localhost:8080. The stack includes Nginx, PHP-FPM, MySQL,
and a scheduler. Use a separate production infrastructure configuration:
the provided Compose setup is for **local demonstration only**.

## API, tests, CI

Read [docs/API.md](docs/API.md) for REST API v1 and authentication.
Run:

```bash
php artisan test
php artisan route:list
```

GitHub Actions runs lint, Composer validation, and tests on `master`.
Another workflow resolves `composer.lock` from the verified
`composer.json` and attempts to push it using the repository GitHub token.
If repository permissions block the push, generate and commit
`composer.lock` on a development machine; deployment without a committed
lockfile is not reproducible.

## Production-readiness checklist

- [ ] Rotate ALL leaked historical secrets, including the `APP_KEY` once committed.
- [ ] Remove sensitive data from Git history with a reviewed rewrite
      (requires coordination; deleting current `.env` is not enough).
- [ ] Commit current `composer.lock` and ensure all CI checks pass.
- [ ] Configure HTTPS, `APP_ENV=production`, `APP_DEBUG=false`, secure
      cookie/session settings, and an authoritative `APP_URL`.
- [ ] Configure SMTP, worker/scheduler, monitored backups, logs, uptime
      monitoring, and incident response.
- [ ] Load-test concurrent booking on target SQL database (SQLite does not
      replicate MySQL row-level locking).
- [ ] Obtain rights to use all images/airline names before commercial use.
- [ ] Commission security review and penetration testing of actual deployment.
- [ ] Sign and tag releases for stronger cryptographic provenance.
- [ ] Complete any real airline/payment provider integrations, PCI scope,
      tax/legal/commercial/regulatory checks before handling money.

See [SECURITY.md](SECURITY.md), [LICENSE](LICENSE), and [NOTICE](NOTICE).

## Ownership and repository visibility

The repository stays public so employers and reviewers can examine it.
This is **source-available**, not an open-source license. License and author
attribution provide notice, not technical copy protection. A public source
can be cloned or altered; neither UI labels nor metadata physically prevent
a malicious party from falsely claiming authorship. Preservation of original
commits and signed releases improves proof of origin. Prior license grants,
if any, may remain applicable to older versions.

**Copyright © 2023–2026 FebianFelix. All rights reserved.**
