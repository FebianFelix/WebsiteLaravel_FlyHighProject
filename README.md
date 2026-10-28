# FlyHigh v2.0 — Presentation Demo

**This is the `demo` branch.** It is for running a complete **fictional flight booking presentation**, not accepting live bookings or payments. The production release source is in [`master`](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/tree/master); ongoing development is [`dev`](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/tree/dev).

## Start in Docker

```bash
git clone --branch demo https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject.git
cd WebsiteLaravel_FlyHighProject
docker compose -f docker-compose.demo.yml up --build -d --wait
```

Open **http://127.0.0.1:8088**.

No MySQL service, airline provider, card processor or SMTP required. Initial Docker image/Composer downloads need internet; once built, the presentation runs locally without external integrations.

## Live presentation

- **Traveler**: `traveler@flyhigh.test` — show search, booking history, wishlist, coupon, seat map, demo e-ticket and invoice.
- **Athena**: `athena@flyhigh.test` — full access, two admin panels, roles and refund approvals.
- **Hephaestus**: `hephaestus@flyhigh.test` — configuration, CMS, flights and promotions.
- **Argus**: `argus@flyhigh.test` — dashboards, audits, bookings and refund monitoring.

Default *local-only* demo password: `DemoPass123!`. Keep the server bound to localhost; don't expose these test credentials on the public internet.

## Presenter tools

```bash
bash scripts/demo-http-smoke.sh http://127.0.0.1:8088
bash scripts/reset-demo.sh
```

Reset deletes only the dedicated presentation data volume. For full setup and presentation walkthroughs, see [docs/DEMO.md](docs/DEMO.md).

### Software scope

All bookings, prices and PDF tickets are fictional; Duffel is read-only and Stripe is test-only in the general source, but both are completely **disabled** in this Docker demo. This branch isn't intended for production deployment. Copyright © 2023–2026 FebianFelix; refer to LICENSE and NOTICE for licensing details.
