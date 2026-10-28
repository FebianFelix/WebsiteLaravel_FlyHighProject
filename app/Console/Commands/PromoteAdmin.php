<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteAdmin extends Command
{
    protected $signature = 'flyhigh:make-admin {email}';
    protected $description = 'Promote an existing FlyHigh user to administrator';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('User tidak ditemukan. Registrasi akun terlebih dahulu.');

            return self::FAILURE;
        }

        if (! $this->confirm('Jadikan '.$user->email.' sebagai administrator?', false)) {
            return self::FAILURE;
        }

        $user->forceFill(['role' => 'admin'])->save();
        $this->info('Role administrator berhasil diberikan.');

        return self::SUCCESS;
    }
}
