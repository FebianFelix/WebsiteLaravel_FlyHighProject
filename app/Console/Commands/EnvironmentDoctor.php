<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class EnvironmentDoctor extends Command
{
    protected $signature = 'flyhigh:doctor {--strict : Fail on warnings as well as errors} {--check-db : Verify current database connectivity}';
    protected $description = 'Validate operational configuration without displaying secret values';

    public function handle(): int
    {
        $environment = app()->environment();
        $errors = [];
        $warnings = [];

        if (! in_array($environment, ['local', 'testing', 'development', 'production', 'demo'], true)) {
            $warnings[] = 'Unknown APP_ENV; confirm runtime environment selection.';
        }
        if (! is_string(config('app.key')) || strlen((string) config('app.key')) < 24) {
            $errors[] = 'APP_KEY is missing or invalid; generate a unique application key.';
        }

        if ($environment === 'production') {
            if (config('app.debug')) $errors[] = 'APP_DEBUG must be false in production.';
            if (! str_starts_with((string) config('app.url'), 'https://')) {
                $errors[] = 'APP_URL must use HTTPS in production.';
            }
            if (! config('session.secure')) $errors[] = 'SESSION_SECURE_COOKIE must be true in production.';
            if (config('database.default') !== 'mysql') $errors[] = 'Production requires its own MySQL database.';
            if (config('flyhigh.demo_payment_enabled')) $errors[] = 'Demo payments are forbidden in production.';
            if (in_array(config('mail.default'), ['log','array'], true)) {
                $warnings[] = 'Production mailer is not configured for real delivery.';
            }
            if (config('flyhigh.booking_mode') !== 'catalog_only') {
                $warnings[] = 'Synthetic reservations explicitly enabled; do not market them as airline tickets.';
            }
        } elseif ($environment === 'demo') {
            if (config('database.default') !== 'sqlite') $errors[] = 'Demo must use isolated SQLite.';
            if (config('flyhigh.payment_gateway') !== 'disabled'
                || config('flyhigh.flight_provider') !== 'disabled') {
                $errors[] = 'Demo must not use live external providers.';
            }
        } elseif ($environment === 'development') {
            if (config('app.debug')) $warnings[] = 'APP_DEBUG should be disabled on shared development hosts.';
        }

        if ($this->option('check-db')) {
            try {
                DB::connection()->getPdo();
            } catch (Throwable $e) {
                $errors[] = 'Database connection failed. Check private server environment and DB service.';
            }
        }
        $this->info('FlyHigh environment: '.$environment);
        foreach ($warnings as $warning) $this->warn('WARNING: '.$warning);
        foreach ($errors as $error) $this->error('ERROR: '.$error);

        if ($errors || ($warnings && $this->option('strict'))) {
            $this->error('Environment configuration failed validation.');
            return self::FAILURE;
        }
        $this->info('Environment configuration checks passed.');
        return self::SUCCESS;
    }
}
