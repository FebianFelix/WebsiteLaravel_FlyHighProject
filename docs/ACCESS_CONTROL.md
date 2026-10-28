# FlyHigh v2.0 — Administrator access control

A custom administrator landing page (`/admin`) shows two large links: **Configuration & Management** and **Dashboard & Monitoring**.

| Capability | Traveler | Athena | Hephaestus | Argus |
|---|---|---|---|---|
| Browse flights + manage own bookings | Yes | Yes | Yes | Yes |
| View admin landing page | No | Yes | Yes | Yes |
| Configure brand, CMS, public content | No | Yes | Yes | No |
| Manage synthetic flight inventory + promos | No | Yes | Yes | No |
| View bookings/analytics/CSV reports | No | Yes | No | Yes |
| Read administrator audit log/refund queue | No | Yes | No | Yes |
| Approve/reject refund requests | No | Yes | No | No |
| Manage staff roles | No | Yes | No | No |

All authorization rules live in `app/Http/Middleware/EnsureAdmin.php` and `routes/web.php`; hidden UI cards are not the security boundary.

**Role names:** Athena is the supreme strategist, Hephaestus the master craftsman/configuration owner, Argus the many-eyed watcher for operations. Legacy `admin` accounts are treated as Athena to avoid surprise lockout after migration.

Grant roles only via an authenticated Athena account (`/admin/users`) or a trusted CLI:

```bash
php artisan flyhigh:make-admin admin@example.com --role=athena
php artisan flyhigh:make-admin operator@example.com --role=hephaestus
php artisan flyhigh:make-admin observer@example.com --role=argus
```

- Public self-registration always defaults to `member`.
- Athena cannot change their own role and cannot remove the final Athena account.
- Role changes and major configuration/content changes are audit-logged.
- Service secrets and payment keys cannot be edited through site CMS; those belong in protected server environments.
- On a role change, administrators should rotate any previously issued API tokens and consider signing out existing sessions; current v2.0 role checks read the database but token scope rotation is not automated.

For payment actions, only Athena can approve provider refunds. Argus can view the queue but cannot execute financial actions.
