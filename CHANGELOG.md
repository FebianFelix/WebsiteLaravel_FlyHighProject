# FlyHigh — Product Evolution & Patch Notes

> **Source of truth:** the actual Git history of [FebianFelix/WebsiteLaravel_FlyHighProject](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject). Early history is described retrospectively; the v1.1+ milestones link to genuine Git commits. No historical timestamps or original authorship are fabricated.

## v0.1 — The university project · Original concept

FlyHigh began as a student-built flight booking website. The initial concept explored a travel homepage, flight listings, form-based booking and basic administration. It was a learning project rather than a commercial airline reservation platform.

## v1.0 — Laravel rebuild & the public GitHub portfolio

The original FlyHigh was rebuilt as a modern Laravel application and published in the repository. The rebuilt foundation included a responsive frontend, authentication and member accounts, flight inventory, bookings, administration, database migrations, a seedable demo dataset, attribution to the original developer, and initial automated tests.

The follow-on business-readiness iteration added expiring seat holds, transactional inventory locking, idempotency keys, simulated payment records, PDF tickets, signed QR verification, REST API tokens, user account security, documentation, Docker/Nginx, and GitHub Actions. Development and production branches and CI/CD were subsequently separated.

## v1.1 — Administrator architecture & content management

[Commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/16c2320c7e63e477a4cf9d404b030965f6d4550b)

- Introduced **Athena** (super admin), **Hephaestus** (configuration) and **Argus** (monitoring).
- Added a two-area administrator landing page and backend permissions per route.
- Added site settings, CMS pages, activity audit events, help center, traveler information, legal information and a proper shared footer.
- Preserved original-project provenance across website, application and database.

## v1.2 — Traveler experience & seat inventory

[Commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/6e2c785ad0d7097ecacce5b6ade607e0e33b6dcf)

- Added account preferences, language selection, time zone, preferred currency and seat preference.
- Added flight wishlists and seat-map selection with unique seat allocations.
- Introduced initial round-trip and multi-city search, while keeping guest browsing public and login required for booking.
- Released Indonesian/English navigation translations and traveler feature tests.

## v1.3 — Checkout architecture, discounts & refunds

[Feature commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/38419590e0b2e33504da85d0de9c870a383df80f)

- Added promotional codes with validity, usage caps and discounts.
- Added base fares, tax configuration, coupon totals, PDF/HTML invoices, a payment-event ledger and refund requests.
- Implemented an opt-in Stripe Checkout **test adapter**, authenticated webhook processing, idempotent capture and manual-reconciliation flags.
- Added refund review tooling, with financial approval restricted to Athena; demo refunds never transfer money.
- Followed up with real fixes and regression tests for invoice details and webhook test signatures.

## v1.4 — Third environment: standalone offline demo

[Commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/6bc6c1a43f2e3e0be3aac69480a3abd5ced0f925)

- Added `APP_ENV=demo`, a dedicated local SQLite database, four fictional admin/traveler accounts and a local bootstrap script.
- Added example demo variables, local payment simulation and synthetic flight data.
- Ensured the demo does not require a live airline API, SMTP, MySQL, Stripe, or remote infrastructure.
- Added a dedicated GitHub Actions demo-boot smoke test.

## v1.5 — Optional airline offer explorer

[Commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/44f36ee7db256a8aa0de012fdbbeaf459dfd4626)

- Added an opt-in Duffel API v2 adapter for **read-only** one-way, return and multi-city offer exploration.
- Added protected input validation, carrier names, price previews, supplier-error handling and provider documentation.
- Airline ticket creation and fulfillment remain intentionally unavailable.

## v1.6 — Multi-leg itinerary transactions

[Commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/06c9cc8f46964ab074e07b3ffc74cf218b6a2e6f)

- Introduced grouped multi-leg reservations, using one atomic database transaction for 2–4 connected flight segments.
- Validated journey order and connections, reserved seats for every segment, and created a unified itinerary reference.
- Added grouped demo confirmation, cancellation and expired-hold handling.
- Added regression tests for atomicity, idempotency and access restrictions.

## v1.7 — Operations oversight & staff management

[Commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/e3f07735908ebf2d43e1fbd1885eaa6280ebb2a2)

- Added an Athena-only role editor with self-demotion and last-super-admin safeguards.
- Added an Argus audit trail, payment reconciliation alerts and refund queue.
- Added server-side tests for privilege boundaries and role assignment changes.

## v1.8 — Fare details & advanced search

[Commit](https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject/commit/db4ac00e1f2861cfb7a3f5356005f85b00dc7e7d)

- Added cabin class, baggage allowance, aircraft type and fare rules to flights and administration.
- Added search filters for cabin and checked-baggage minimum; corrected multi-city first-leg selection.
- Made registration resume the intended destination and blocked live Stripe secret keys in the test-only checkout integration.

## v1.9 — Packaging and distribution

- Added release packaging tooling and a marketplace seller handbook.
- Consolidated version, licensing and commercial-readiness documentation.
- Clarified supported integrations, default-off feature flags and the distinction between a **demo**, a **sellable source-code package**, and an **operational airline-ticket business**.

## v2.0.0 — FlyHigh Business Edition

- Release of the integrated Laravel booking management package: travel discovery, configurable synthetic inventory, account preferences, multi-role administration, CMS, seat selection, promotional pricing, itineraries, invoices, demo payments, refund requests and audit reporting.
- Added three documented environments: **offline demo**, **isolated development**, and **approval-gated production**.
- Extended CI with SQLite tests, MySQL integration tests, a demo bootstrap smoke test and Docker image publishing.
- Provided sample operator configurations, release notes and an honest deployment/security checklist.

### Critical product boundaries

**v2.0 is production-oriented software and a source-code product, not a certified live OTA.** It is not a valid boarding pass/airline ticket issuer. Duffel is currently read-only. Stripe is test-mode-only. Production deployment, airline supplier authorization, PCI/legal/tax review, performance/security testing and paid customer support are required before running a real-money business.

All v1.1–v2.0 changes are real commits made during this upgrade. This changelog explains feature milestones and does **not** pretend that those commits were authored on different historical dates.
