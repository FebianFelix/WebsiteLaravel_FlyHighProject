# FlyHigh v2.0 — standalone demo

This third environment complements **development** and **production**. The demo environment does not need MySQL, Stripe, Duffel, SMTP or GitHub Actions deployment. It uses PHP, Composer, local assets, and a local SQLite file. Installing Composer dependencies on a fresh machine can require network access; afterwards the demo itself does not call external services.

## Quick start

```bash
git clone https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject.git
cd WebsiteLaravel_FlyHighProject
git checkout dev
bash scripts/start-demo.sh
```

The script refuses to overwrite a non-demo `.env`. It copies `.env.demo.example` into ignored `.env` if missing, creates the ignored `database/demo.sqlite` file, installs Composer dependencies, generates a unique APP_KEY, migrates the demo DB, seeds fictional flights, coupon `DEMO10`, a sample CMS page, and four demonstration accounts.

| Login email | Role | Access |
| --- | --- | --- |
| athena@flyhigh.test | Athena | Config + monitoring + refund approval |
| hephaestus@flyhigh.test | Hephaestus | Config, flights, CMS, promo |
| argus@flyhigh.test | Argus | Dashboard, bookings, monitoring |
| traveler@flyhigh.test | Traveler | Browse, reserve and customize profile |

Default local demo password: `DemoPass123!`; override via `FLYHIGH_DEMO_PASSWORD` before seeding. All credentials are **fictional**. Do **not** expose an unprotected demo with administrator accounts to the internet. Use localhost, private tunnels with authentication, or a tightly restricted disposable host with scheduled resets.

For a clean reset on an isolated demo system: delete `database/demo.sqlite` only after confirming that the current `.env` has `APP_ENV=demo`, then rerun the script. Never reset an actual development or production database.

## Important limitations

- Search and bookings are synthetic; no real airline tickets or orders are issued.
- Offline demo payments confirm records without transferring money.
- Offline test emails are written to logs.
- Built-in style assets and photos are local; Google Fonts are disabled in demo.
- Flight API (Duffel) and live checkout (Stripe) are disabled, with empty tokens by default.
- Do not enter real payment details, government identifiers or passport numbers.
