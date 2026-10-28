# FlyHigh delivery and Git branch policy

A deliberate three-branch structure, with deployable and disposable environments kept separate.

```text
feature/* ────── PR ──────> dev ────── PR + review ──────> master
                             │                              │
                     tests + container                 tests + container
                             │                              │
                  isolated development              protected production
                  (only if enabled)                  (only if enabled)

demo: presentation-only branch ── isolated Docker/SQLite ── local browser
```

**`demo`**: portable sales demonstrations. Owns `docker-compose.demo.yml`, fictional users, invoices, bookings and example refunds. Docker binds localhost only. Separate CI builds and boots the demo and tests its HTTP, login and role navigation. It never publishes the deployable image or receives deployment secrets.

**`dev`**: shared integration branch. Developers use feature branches and PRs; full PHPUnit on SQLite and MySQL, dependency lock validation, PHP lint and Docker build run before deployment. The deployed development environment uses a dedicated database and HTTPS; its secrets must not come from demo. `APP_ENV=development`.

**`master`**: stable release branch; only `dev → master` pull requests are accepted by the policy check. GitHub branch protection and required reviews must be configured in repository Settings (the workflow cannot force the GitHub administrator to do so). Production CD is **off by default**, requires protected GitHub Environments and separate SSH credentials, and uses digest-pinned images. `APP_ENV=production`, `APP_DEBUG=false`, `FLYHIGH_BOOKING_MODE=catalog_only`, `FLYHIGH_DEMO_PAYMENTS=false`.

## Required operational decisions

- Do not merge the presentation fixtures from `demo` into `master`.
- When a feature is needed in both real software and demo, build it in a feature branch targeting `dev`, release through `master`, then merge or cherry-pick the verified feature into `demo` and rerun its browser smoke suite.
- Keep real credentials solely in server env/secrets managers; never in Git or archived source ZIPs.
- The production environment must have an independent SQL database, APP_KEY, TLS termination, secure cookies, backups, rate limiting, mail service and monitoring. No deployment from the repository has been made to an actual server without these settings.
- Release `master` via PRs only; protect `dev` with required CI.
- Never report a CI green build as proof of a security audit, a live airline integration, or a successful real production deployment.

See `docs/ENVIRONMENTS.md` for CI/CD setup and `docs/OPERATIONS.md` for operational obligations.
