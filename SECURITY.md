# FlyHigh Security

## Reporting vulnerabilities

Please report vulnerabilities privately using this repository's GitHub
Security Advisories, rather than disclosing exploitable details in public
issues.

## Secure deployment baseline

- PHP 8.3+, supported Laravel and dependencies, HTTPS and secure cookies.
- Document root must be `public/` only.
- Use a newly generated `APP_KEY`. A previously committed key existed in
  Git history; revoke or rotate all previously exposed credentials.
- `APP_DEBUG=false`, `APP_ENV=production`, real database credentials in
  server-side secret management, not in Git.
- No real payments or airline ticket issuance. Simulator is off by default
  when `FLYHIGH_DEMO_PAYMENTS` is absent.
- Run scheduler every minute to expire holds; maintain database backups.
- Never use sample Docker credentials on a public network.
- Issue API tokens individually; revoke tokens that are no longer needed.
- Obtain rights for all photographic assets before commercial usage.

## Security boundaries

The PDF/QR verification route uses Laravel temporary signed URLs. These prove
only the status of a FlyHigh demo booking; they are not airline confirmations.
API booking endpoints require Sanctum tokens or authenticated first-party sessions.
User-owned bookings are checked server-side, and the admin middleware protects
operations.

GitHub repository provenance is *not* encryption, DRM, or copy prevention.
