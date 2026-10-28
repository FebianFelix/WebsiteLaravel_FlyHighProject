# FlyHigh v2.0.0 — Laravel Flight Reservation Management System

> **© 2023–2026 FebianFelix · Source-available software.** [Canonical GitHub repository](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject).
>
> FlyHigh is an advanced, configurable **synthetic flight-reservation software platform**. The repository does not issue legal airline tickets, create airline orders, or run live card charges. It is suitable for software demonstrations, developer portfolios, and customization projects. A functioning OTA requires licensed providers, live inventory/order fulfillment, operational verification, and compliance.

## Meet FlyHigh 2.0

Travelers can browse flights without logging in, explore one-way/return/multi-city search, view cabin and baggage details, save a wishlist, sign up/login when reserving, choose seats, reserve connected itineraries, use promotional codes, access invoice/ticket PDFs, and customize account preferences. The reservation system includes a transactional inventory lock, unique booking references and a time-limited hold.

**Administrator workspace:**

| Role | Mythology | Allowed area |
|---|---|---|
| **Athena** | Wisdom & strategy | Both configuration and operations, plus staff roles and refund decisions |
| **Hephaestus** | Craftsmanship | Configuration: brand, CMS, flights, pricing promos |
| **Argus** | All-seeing watcher | Monitoring: analytics, bookings, CSV export, refunds, audit events |
| **Traveler** | User | Personal booking history and account controls |

After an admin signs in, they see a **two-panel Control Center**: configuration and monitoring. Routes enforce the same privileges on the server. Existing legacy `admin` accounts retain super-admin access for migration compatibility.

### Feature matrix

| Category | Included in v2.0 |
|---|---|
| Flight search | One way, return and multi-city discovery, class/baggage filters |
| Booking | 1–6 passengers, seat map, expiry, idempotency, SQL transaction |
| Multi-leg | 2–4 connected segments reserved atomically in one itinerary |
| Customer experience | Signup/login, password reset, email verification, profile, preferences, wishlist |
| CMS | Public pages, safe text content, branding, support contacts, site announcements |
| Admin | Athena / Hephaestus / Argus with access controls and audits |
| Commerce simulation | Coupons, fare breakdown, invoice PDFs, demo payment, refund requests |
| External adapters | Duffel v2 **read-only** offers; Stripe Checkout **test keys only** |
| API | Laravel Sanctum token-authenticated booking and flight endpoints |
| Quality & delivery | SQLite/MySQL tests, standalone demo smoke, Docker/GHCR, dev/master CI/CD |
| Provenance | LICENSE, NOTICE, metadata, DB provenance, source history and footer |

**Documentation:** [Release notes](docs/RELEASE_NOTES_v2.md) · [Version history](CHANGELOG.md) · [Seller guide](docs/SELLER_GUIDE.md) · [API](docs/API.md) · [Provider adapters](docs/INTEGRATIONS.md).

## Run an offline demo in minutes

Requirements: **PHP 8.3+, Composer, SQLite PDO extension**. At runtime only local components are used: no MySQL server, Stripe, Duffel, SMTP, or GitHub authentication. An initial Composer dependency download may require network access unless dependencies are cached.

```bash
git clone https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject.git
cd WebsiteLaravel_FlyHighProject
bash scripts/start-demo.sh
```

Open **http://127.0.0.1:8000**. The script copies ignored `.env.demo.example` to `.env`, creates `database/demo.sqlite`, installs dependencies, generates a private application key, migrates, seeds fictional data and serves locally.

| Fictional account | E-mail | Demo access |
|---|---|---|
| Athena | `athena@flyhigh.test` | Both admin panels |
| Hephaestus | `hephaestus@flyhigh.test` | Configuration |
| Argus | `argus@flyhigh.test` | Monitoring |
| Traveler | `traveler@flyhigh.test` | User booking |

Demo password (local only): `DemoPass123!` or the configured `FLYHIGH_DEMO_PASSWORD`. **Do not publish default admin credentials on an internet-facing demo**. Use access control and disposable data resets. See [DEMO.md](docs/DEMO.md).

## Standard development setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure a new MySQL database OR SQLite database file in .env.
php artisan migrate --seed
php artisan serve
```

Start the scheduler in a separate terminal to expire holds: `php artisan schedule:work`. Do not run against the original 2023 database without migration planning/backups. The old `tbl_*` layout has **no** automatic data migration.

To give an **existing** account a role through the private server CLI:

```bash
php artisan flyhigh:make-admin admin@example.com --role=athena
php artisan flyhigh:make-admin config@example.com --role=hephaestus
php artisan flyhigh:make-admin ops@example.com --role=argus
```

Each command asks for confirmation; no predefined administrator account is seeded outside demo.

For a Docker-based local MySQL stack, configure a private `.env` and strong passwords, then run `docker compose up --build -d` and `docker compose exec app php artisan migrate --seed`.

## Application safety and deployment preflight

The application **defaults to catalog-only on a production server** (`FLYHIGH_BOOKING_MODE=catalog_only`), so public visitors can browse inventory while the server refuses synthetic reservations. To intentionally allow fictional reservations, an operator must explicitly set `simulation`. Real airline orders and ticket issuing are **not implemented**. Demo payment settlement is blocked by code in `APP_ENV=production`, even if an operator accidentally sets a demo payment flag.

Before database migrations or release promotion on a server, run `php artisan flyhigh:doctor --check-db --strict` after provisioning your private environment. This validates keys, database reachability, HTTPS and secure sessions without printing secrets. Sample database seeding is forbidden on production servers.

For a reliable local presentation, use the [dedicated `demo` branch](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/tree/demo), which includes a one-command Docker Compose setup with offline SQLite data, fictional booking history, admin accounts and a real HTTP smoke test.

## Three environments

| Environment | Code target | Database | Deployment |
|---|---|---|---|
| **Demo** | `demo` branch | Dedicated SQLite + fictional users | Docker Compose for local presentation |
| **Development** | `dev` branch | Separate dev MySQL | Conditional auto-CD |
| **Production** | `master` branch | Separate production MySQL | Protected approval gate before CD |

Production and development require separate `APP_KEY`, databases, Docker projects, storage, SSH identities and secret management. Deployment remains OFF until an operator configures the GitHub Environments and explicit enable flags. See [ENVIRONMENTS.md](docs/ENVIRONMENTS.md).

### Provider feature flags

```env
FLYHIGH_DEMO_PAYMENTS=true
FLYHIGH_PAYMENT_GATEWAY=disabled
FLYHIGH_FLIGHT_PROVIDER=disabled
FLYHIGH_TAX_PERCENT=0
```

- Stripe adapter uses `sk_test_...` keys **only**, plus verified signing secret; **never** live payments in this source release.
- Duffel adapter searches provider offers but does **not** create airline orders.
- A synthetic Flight booking and a Duffel provider offer are **different** records: buying an actual flight requires additional integration.
- No real refund can be promised without provider-side reconciliation and legal terms.

See [INTEGRATIONS.md](docs/INTEGRATIONS.md) before editing these values.

## CI quality gates

The GitHub Actions pipeline automatically validates Composer, runs PHP syntax lint, compiles routes and executes **SQLite and MySQL 8 test suites**, boots the offline demo with migrations/fixtures and builds a Docker container pushed to GHCR by image digest. PRs to `master` must originate from `dev`. Operator must still apply actual GitHub branch rules and protected environment approvals.

```bash
php artisan test
php artisan route:list
bash -n scripts/start-demo.sh scripts/package-release.sh scripts/cd-deploy.sh
```

Generate a clean source ZIP for marketplace review:

```bash
bash scripts/package-release.sh
```

The archive/checksum appear in ignored `.releases/`. Verify third-party asset redistribution rights before offering it to buyers.

## Security, deployment and license caveats

- Rotate any secret from historical Git commits, including historical `APP_KEY`; `.env` no longer appears at current HEAD but Git history may still contain the prior one.
- Require HTTPS, secure session cookies, secret manager, SMTP/queue/scheduler, reliable backup, logging/monitoring, rate limiting, testing on actual MySQL concurrency and professional security review before launching customer data.
- The original university project may contain third-party photos/brand references. **Commercial redistribution rights must be verified**, not assumed.
- The public repository is **source-available**, not automatically permissive open source. Current `LICENSE` and `NOTICE` govern the published version; **old grants remain valid** if an earlier version was licensed differently. Public source can still be cloned; attribution is not DRM.
- Do not describe FlyHigh 2.0 as a full OTA: real ticket issuance, compliant invoicing, cross-provider flight booking, live-money payments, full localization, airline settlement, and actual production deployment are beyond its current scope.

**Copyright © 2023–2026 FebianFelix. All rights reserved.**
