# FlyHigh v2.0 — Marketplace Seller & Operator Guide

## Product positioning

**FlyHigh Business Edition — Laravel Flight Booking & Reservation Management System**

FlyHigh is a **customizable source-code application** with synthetic inventory, flight discovery, seat holds, account/login, administrator roles, business dashboard, a CMS, promotional pricing, grouped itineraries, PDF demo tickets and invoices, Stripe TEST adapter and read-only Duffel airline-offer search. Its market is developers, software agencies, students and businesses commissioning a tailored reservation workflow.

**Never claim:** real airline ticket issuing, a production airline distribution connection, live card processing, certified PCI compliance, or guaranteed zero-downtime deployment. None of those have been delivered by v2.0.

## Source-code delivery

1. Start from the verified `master` release commit and confirm the full CI pipeline is green.
2. Run `bash scripts/package-release.sh` from a clean clone. It creates a source ZIP and SHA-256 checksum in the ignored `.releases/` directory, excluding local secrets and runtime data.
3. Check the contents, third-party asset licenses, dependencies, README, release notes and install instructions.
4. Upload the source package through the marketplace's official file-delivery channel.
5. Provide an independently hosted, access-controlled **demo** on disposable infrastructure, not production credentials. The demo's default sample passwords must never be presented to an untrusted public audience without network access control and data resets.

## Illustrative listing copy

**Title:** FlyHigh — Laravel Flight Reservation System with Multi-Role Admin, CMS & Demo Payments

**Description:** Build a customizable flight reservation experience with Laravel 13. Browse synthetic flights as a guest, register to reserve seats, manage itinerary segments, discounts and account preferences, and run separate Athena/Hephaestus/Argus administrator workflows. Includes PDF invoices, demo e-tickets, REST API, Docker, CI and an offline SQLite demonstration environment. Optional provider adapters are provided for Stripe **test mode** and read-only Duffel offer discovery. Real airline ticket fulfillment and live card processing are NOT included.

**Suitable categories:** PHP scripts, booking/reservation management, Laravel applications, travel templates.

**Not suitable descriptions:** live airline booking engine, fully integrated OTA, guaranteed airline ticketing, PCI-compliant payment application.

## Buyer setup and responsibilities

- Buyer configures PHP 8.3+, Composer, web server, private `.env`, unique `APP_KEY`, separate database, mail and HTTPS.
- Admin operator provisions Athena via CLI `php artisan flyhigh:make-admin user@example.com --role=athena`. Hephaestus and Argus roles use the same command with a different role flag.
- Demo previews use `bash scripts/start-demo.sh` and their **own SQLite database**. The production installation must not contain default demo user/password combinations.
- All payment/provider keys must be supplied by the buyer, never published with the package.
- The buyer must write and publish legally reviewed refund/privacy/terms policies and secure their runtime operations.
- The buyer must validate the right to use all image assets, brand names and design resources for commercial distribution.

## License and open repository caveat

This project is **publicly viewable source code** using the custom source-available `LICENSE`, which must be reviewed for suitability before selling licenses. The mere existence of a public repository means users can inspect and clone code; no attribution header can physically prevent a clone or the removal of footer branding. Earlier versions may be subject to previously granted licenses; changing the current license does not retroactively invalidate earlier grants.

Before using CodeCanyon, Codester or another marketplace, read the vendor's terms and confirm the commercial distribution rights for the app, included third-party packages and media. Consider legal review of the product license and a separate commercial support agreement.

## Suggested commercial packaging (not market price verification)

- Regular source license: price chosen by the seller according to features and marketplace fee structure.
- Optional paid installation/setup: billed separately.
- Optional customization, new airline API order creation, admin modules and maintenance: scoped projects with written acceptance criteria.
- Support: document operating hours, what bug fixes are included, response targets and upgrade policy.

## Release acceptance checklist

- [ ] CI green for SQLite, MySQL, demo boot and Docker build
- [ ] Core authorization tests cover each admin role
- [ ] No live Stripe secret / real customer data in Git or ZIP
- [ ] No demo users in production database
- [ ] Run staging UI/device and accessibility checks
- [ ] Verify server backup, HTTPS, scheduler and monitoring
- [ ] Confirm buyer understands there is **no airline order fulfillment**
- [ ] Check all third-party asset redistribution rights
- [ ] Review LICENSE/NOTICE, version and changelog
- [ ] Prepare screenshots, demo instructions and privacy notice
