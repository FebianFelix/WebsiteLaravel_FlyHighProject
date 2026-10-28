## Target branch and release intent

- [ ] Feature/fix PR targets `dev`
- [ ] Production promotion PR targets `master` **from `dev`**

## Validation

- [ ] SQLite unit/feature CI
- [ ] MySQL integration CI
- [ ] Deployment scripts / Docker build (push)
- [ ] No .env, APP_KEY, real credential or customer data committed
- [ ] Schema migration is backward compatible and reviewed
- [ ] Demo payments remain explicitly non-monetary

## Release

Describe behavioral changes, deployment dependencies, and rollback strategy.
For production changes, confirm environment approval and pre-deployment DB backup.
