# FlyHigh v2.0 — Operations, monitoring, and support

## Dashboard

Argus and Athena can see bookings, confirmed/pending/cancelled state, seven-day trend, gross **simulated** booking value, user count, and recent reservations. Audit events capture CMS edits, promotional setup, site settings, and role changes. This is not an authoritative revenue ledger for live airline operations.

## Reconciliation queue

An accepted Stripe TEST event must match the stored Checkout Session ID, exact IDR total and a still-valid reservation. Late/mismatched webhook events go into `payment_events` with `requires_review`. This does **not** automatically issue airline tickets. Operators should investigate the Stripe provider dashboard and correct the refund/booking lifecycle through an auditable process.

Refund requests appear in the Argus queue; Athena has the exclusive approve/reject workflow. A successful refund API response can represent a pending provider refund; do not promise completion until the provider confirms it.

## Scheduler

The Laravel scheduler must run in development/production to expire unpaid holds. The included container deployment has a separate scheduler service. Make sure only one scheduler runs a given environment, and monitor failed jobs.

## Running three environments

- Demo: `APP_ENV=demo`, `sqlite`, test fixture accounts. Localhost only.
- Development: `APP_ENV=development`, isolated MySQL and credentials. `dev` branch.
- Production: `APP_ENV=production`, isolated MySQL and credentials. `master` branch, protected approval. Production payment and airline fulfillment is not live in this software release.

## Before a real commercial deployment

Review server firewall, HTTPS and secure cookies, backup/recovery, failover/monitoring, privacy/consent, SMTP, queued notifications, rate limits, secure session handling, vulnerability/penetration assessments, MySQL concurrency and load tests, and legal verification for all third-party imagery. Provider agreements are required before accepting live airline-ticket orders.

The repository exposes original source attribution and historical commits, but cloning cannot be technically prevented.
