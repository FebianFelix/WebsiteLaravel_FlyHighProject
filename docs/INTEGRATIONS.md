# FlyHigh v2.0 — external integration guide

## Scope

The default install is a **synthetic reservation platform**, not an OTA or airline ticket issuer. External adapters are opt-in. They never run in the standalone offline demo.

### Duffel read-only offer search

Set these server-side values:
```env
FLYHIGH_FLIGHT_PROVIDER=duffel
DUFFEL_ACCESS_TOKEN=REPLACE_WITH_ISSUED_TEST_TOKEN
```
Then visit `/air-offers`. The backend uses Duffel v2 `POST /air/offer_requests` with IATA codes, trip slices and adult passengers. It displays the operating carrier on the search results as required by the supplier. Search offers expire; it intentionally does **not** create orders or issue tickets. Additional work is required to handle individual traveler legal names, baggage, fare rules, payment method availability, pricing, offer expiry, airline ticket fulfillment, refunds and financial settlement. Production use requires supplier approval and compliance.

Reference: https://duffel.com/docs/api/v2/offer-requests

### Stripe Checkout — test mode only

Set these server-side values:
```env
FLYHIGH_PAYMENT_GATEWAY=stripe
STRIPE_SECRET_KEY=REPLACE_WITH_ISSUED_SECRET
STRIPE_WEBHOOK_SECRET=REPLACE_WITH_SIGNING_SECRET
FLYHIGH_DEMO_PAYMENTS=false
```

Configure the Stripe webhook endpoint `POST https://YOUR-DOMAIN/api/v1/payments/stripe/webhook` for `checkout.session.completed`. Verification checks the raw-body HMAC signature, timestamp, paid status, currency, stored Checkout Session identifier and amount. Only a valid, current reservation can be confirmed. Duplicate event IDs are ignored. Late or mismatched paid events are stored in `payment_events` with `requires_review`, do not issue a demo ticket, and require provider-side reconciliation or refunds. Stripe Refunds API is available to Athena administrators from the refund review panel, with an idempotency key. Pending refunds should be monitored in Stripe until final state is confirmed.

**FlyHigh v2.0 deliberately rejects live Stripe secret keys**. Before any future real-money extension: test webhooks and refunds with Stripe test mode; configure monitoring, complete legal/consumer refund terms, configure secure TLS, complete reconciliation, dispute/chargeback handling, record retention, tax review, fraud checks, and verify eligibility for live payment processing. FlyHigh does not fulfill airlines' actual ticket obligations.

### Security and operational constraints

Do not store provider credentials in CMS/site_settings. Keep them in a secrets manager or private server environment. Do not enable these integrations in public demos. Review data handling and rate limits. The app must use real issuer details and operator policies, not FlyHigh's default sample legal text, before production commerce.
