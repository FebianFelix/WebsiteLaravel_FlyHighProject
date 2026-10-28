<?php

namespace App\Console\Commands;

use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Console\Command;

class PromoteAdmin extends Command
{
    protected $signature = 'flyhigh:make-admin {email} {--role=athena : athena, hephaestus or argus}';
    protected $description = 'Assign a named administrator role to an existing account';

    public function handle(): int
    {
        $role = (string) $this->option('role');
        if (! in_array($role, [User::ATHENA, User::HEPHAESTUS, User::ARGUS], true)) {
            $this->error('Invalid role. Use athena, hephaestus, or argus.');
            return self::FAILURE;
        }
        $user = User::where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('User not found. Register first.');
            return self::FAILURE;
        }
        if (! $this->confirm('Assign role '.$role.' to '.$user->email.'?', false)) {
            return self::FAILURE;
        }
        $oldRole = $user->role;
        $user->forceFill(['role' => $role])->save();
        AuditEvent::create(['actor_user_id' => $user->id, 'action' => 'role.assigned.cli', 'subject' => $user->email, 'details' => ['old_role' => $oldRole, 'new_role' => $role]]);
        $this->info('Assigned '.$role.' successfully.');
        return self::SUCCESS;
    }
}
