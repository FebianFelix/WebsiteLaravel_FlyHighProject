# FlyHigh demo branch — hands-on presentation

The **`demo` branch** is an independently runnable, local-only FlyHigh sales presentation. It uses fictional bookings and local SQLite; no MySQL, Stripe, Duffel, SMTP, Redis, external airline or payment service is needed at runtime.

## Recommended: one Docker command

Prerequisites: **Docker Engine + Docker Compose plugin**. No separately installed PHP, Composer or MySQL required.

```bash
git clone --branch demo https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject.git
cd WebsiteLaravel_FlyHighProject
docker compose -f docker-compose.demo.yml up --build -d --wait
```

Open **http://127.0.0.1:8088** in your browser. Docker automatically installs PHP/Composer dependencies during its initial image build (network access required for first image and dependency download), generates a private Laravel APP_KEY, creates a persistent local SQLite database, migrates it, and seeds sample users, flights, bookings, paid demo receipts, discounts and a refund request. The running demo makes no requests to external providers.

```bash
docker compose -f docker-compose.demo.yml ps
docker compose -f docker-compose.demo.yml logs -f demo
bash scripts/demo-http-smoke.sh http://127.0.0.1:8088
```

The demo binds **127.0.0.1 only** (not available from another computer by default). Project name `flyhigh-presentation`, local host port `8088`. To change local port: `FLYHIGH_DEMO_PORT=8090 docker compose -f docker-compose.demo.yml up --build -d --wait`.

## Offline after initial setup

Once base images and Composer dependencies have been downloaded and built, you can disconnect from the internet and continue presenting the running Docker application. All JS/CSS/images are local; Google Fonts and external API adapters are disabled in demo mode. A new computer's first installation still needs network access to acquire Docker images and Composer dependencies.

## Login and demo scenarios

| Account | Login | Roles |
|---|---|---|
| Athena | `athena@flyhigh.test` | Both admin panels, user role management, refund approval |
| Hephaestus | `hephaestus@flyhigh.test` | Configuration, CMS, promos, synthetic flight CRUD |
| Argus | `argus@flyhigh.test` | Monitoring, bookings, reports, audits, refund queue |
| Traveler | `traveler@flyhigh.test` | Wishlist, seat reservation, itinerary, simulated payment |

All accounts use **`DemoPass123!`** by default. It is intentionally a local sample password; change it through `FLYHIGH_DEMO_PASSWORD` before seeding if sharing with others. **Never publish these privileged demo accounts over an unauthenticated internet-facing server**.

Recommended live walkthrough:

1. Open homepage as guest, explore search filters, cabin/baggage, Help, and Info.
2. Log in as Traveler; inspect existing confirmed and pending reservations, PDF invoice/e-ticket, promo `DEMO10` or `FLYHIGH20`, wishlist, user preferences and book a flight.
3. Log in as Athena; select **Configuration** and modify the banner or CMS; return to **Monitoring** for the populated dashboard/refund queue. Demonstrate managing user roles.
4. Log in as Hephaestus and show that Monitoring access is blocked. Log in as Argus and show that Configuration access is blocked.

## Clean reset before the next presentation

Demo data survives a normal container restart. To remove only the presentation Compose project's volume and restore the original fictional data, run:

```bash
bash scripts/reset-demo.sh
```

The script requires typing `RESET-DEMO`. `bash scripts/reset-demo.sh --yes` skips confirmation **and permanently deletes the isolated demo bookings, sessions and local database**. Neither command touches the development or production databases.

```bash
docker compose -f docker-compose.demo.yml down
```
stops the demo without deleting its persistent data.

## Alternative: PHP locally

If PHP 8.3+, pdo_sqlite, Composer and the other Laravel extensions are installed, run:

```bash
git checkout demo
bash scripts/start-demo.sh
```

This uses the ignored local `.env` created from `.env.demo.example` and a separate local SQLite database `database/demo.sqlite` at http://127.0.0.1:8000. The launcher refuses an existing non-demo environment. The local PHP launcher and Docker Compose launcher keep different databases.

## Safety and scope

The demo **does not issue airline tickets or transfer money**. All routes, invoices, emails and admin account names are fictional. Don't enter real identity/payment data. This demo is a presentation asset, **not a public multi-tenant hosted SaaS**. Demo is deliberately isolated from `dev` and `master`, whose long-lived database credentials and deploy flows must never be copied into this branch.
