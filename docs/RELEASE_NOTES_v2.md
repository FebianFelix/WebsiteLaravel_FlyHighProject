# FlyHigh 2.0.0 — release notes

**Codebase:** Laravel 13 / PHP 8.3 / MySQL 8 + SQLite for offline demo.

## Shipped features

- Guest-accessible search, featured offers, filters for origin/destination, date, cabin, airline, price and baggage.
- Registered member booking with expiring seat holds, idempotency keys, occupancy checks, 1–6 passengers, visual seat selection and seat reassignment on expiration/cancellation.
- Grouped 2–4-leg synthetic itineraries with atomic inventory reservations and grouped confirmation/cancellation.
- Promo codes, optional tax illustrations, invoice PDF, printable reservation data, demo e-ticket PDF and signed QR verification.
- Accounts with login, registration, password reset, email verification, preferences, language navigation selection, wishlist and scoped personal API tokens.
- Admin role architecture: **Athena** full, **Hephaestus** config only, **Argus** monitoring only; landing area selector, CMS, settings, flight administration, analytics, audit trail, user-role administration, refund request queue.
- Optional Duffel v2 **offer-search-only** adapter and Stripe **test-only** Checkout/verified webhooks. Neither integration fulfills an actual airline order.
- Offline demo environment with standalone SQLite and predefined fictional accounts; deployment-oriented development and production environments with separate secrets and GitHub CI/CD.
- Source code release packaging, changelog, seller and setup guides.

## Technical safety

The source includes CSRF protection, Eloquent query binding, role middleware for every privileged endpoint, signed URL validation, API token authentication, server-side validation, password hashes, transactional flight inventory reservation, payment-webhook HMAC verification, and idempotent event/booking processing.

These controls do **not** constitute a complete security certification. Production operator responsibilities include security auditing, stress/concurrency testing, mail setup, session/TLS configuration, secret rotation, real backups, legal/privacy policy, rate limiting, fraud prevention and safe deployment approval.

## Not included in v2.0

- Real airline booking/order creation, ticket issuance, GDS/PNR, check-in or automatic airline cancellation.
- Real live-money Stripe processing; only Stripe **test-mode** keys are permitted in the provided integration.
- Real hotel, tour and visa booking modules.
- Certified compliance for tax invoices, PCI DSS, GDPR or regional travel law.
- Multi-tenant subscription/SaaS billing and fully localized content throughout every screen.
- Production live deployment with final operator domain, SMTP, SSL, alerts and backups (server credentials are not configured in source).
- Load/penetration test attestation, legal product-license review and confirmed redistribution rights for old portfolio image assets.

## Version history

See [CHANGELOG.md](../CHANGELOG.md). Milestone commits are genuine and all carry their actual Git timestamps; the historic v0.1 description is retrospective rather than backdated.

## Launch path

1. Review [SELLER_GUIDE.md](SELLER_GUIDE.md) and third-party assets.
2. Start offline demo from [DEMO.md](DEMO.md).
3. Test optional provider adapters from [INTEGRATIONS.md](INTEGRATIONS.md).
4. Set up two server environments and approval rules as documented in [ENVIRONMENTS.md](ENVIRONMENTS.md).
5. Run the full test suite and build source ZIP with `scripts/package-release.sh`.
6. Review the actual `LICENSE` and old repository history before shipping commercial distribution.
