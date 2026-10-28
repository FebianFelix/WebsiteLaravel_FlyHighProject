<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users', ['users' => User::orderBy('id')->paginate(25)]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['member', User::ATHENA, User::HEPHAESTUS, User::ARGUS])],
        ]);
        if ($request->user()->id === $user->id) {
            throw ValidationException::withMessages(['role'=>'You cannot change your own administrator role.']);
        }
        DB::transaction(function () use ($request, $user, $data) {
            $target = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $currentRole = $target->role;
            if (in_array($currentRole, ['admin', User::ATHENA], true)
                && $data['role'] !== User::ATHENA
                && User::whereIn('role', ['admin', User::ATHENA])->count() <= 1) {
                throw ValidationException::withMessages(['role'=>'Cannot remove the last Athena administrator.']);
            }
            $target->forceFill(['role'=>$data['role']])->save();
            AuditEvent::create([
                'actor_user_id'=>$request->user()->id, 'action'=>'user.role.changed',
                'subject'=>(string) $target->id,
                'details'=>['old_role'=>$currentRole,'new_role'=>$data['role']],
            ]);
        }, 3);
        return back()->with('success','Role updated.');
    }
}
