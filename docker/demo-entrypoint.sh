#!/bin/sh
# No network integrations at runtime; isolated SQLite + persistent demo APP_KEY.
set -eu
cd /var/www/html
if [ "${APP_ENV:-}" != demo ] || [ "${DB_CONNECTION:-}" != sqlite ]; then
    echo "Refusing presentation boot without APP_ENV=demo and SQLite." >&2
    exit 1
fi
if [ "${FLYHIGH_PAYMENT_GATEWAY:-disabled}" != disabled ] || [ "${FLYHIGH_FLIGHT_PROVIDER:-disabled}" != disabled ]; then
    echo "Demo cannot connect to payment or airline providers." >&2
    exit 1
fi

umask 077
mkdir -p storage/demo storage/framework/cache/data storage/framework/views storage/framework/sessions storage/logs
key_file="storage/demo/.app-key"
if [ ! -s "$key_file" ]; then
    php artisan key:generate --show > "$key_file"
fi
export APP_KEY="$(cat "$key_file")"
unset STRIPE_SECRET_KEY STRIPE_WEBHOOK_SECRET DUFFEL_ACCESS_TOKEN
touch "$DB_DATABASE"
php artisan optimize:clear
php artisan migrate --force
if [ ! -f storage/demo/.presentation-seeded ]; then
    php artisan db:seed --class=PresentationSeeder --force
    touch storage/demo/.presentation-seeded
fi
echo "FlyHigh presentation available on container port 8000."
exec php artisan serve --host=0.0.0.0 --port=8000 --no-reload
