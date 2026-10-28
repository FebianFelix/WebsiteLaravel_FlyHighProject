@extends('layouts.app')
@section('title','Identity & role management')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.portal') }}">← Control center</a><span class="eyebrow">ATHENA · ACCESS CONTROL</span><h1>People & roles</h1><p>Only Athena can assign or revoke administrative roles. Self-demotion and removal of the last Athena are prohibited.</p></div></section>
<section class="section container"><div class="card table-wrap"><table><thead><tr><th>Account</th><th>Email</th><th>Current role</th><th>Permissions</th></tr></thead><tbody>
@foreach($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roleLabel() }}</td><td>
@if($user->id !== auth()->id())<form method="POST" action="{{ route('admin.users.role',$user) }}" class="inline-actions">@csrf @method('PATCH')
<select name="role" aria-label="Choose administrator role">@foreach(['member'=>'Traveler','athena'=>'Athena · All','hephaestus'=>'Hephaestus · Configuration','argus'=>'Argus · Monitoring'] as $value=>$label)<option value="{{ $value }}" {{ $user->role === $value ? 'selected':'' }}>{{ $label }}</option>@endforeach</select>
<button type="submit" class="btn btn-outline btn-small">Update</button></form>@else<span class="muted">Your account</span>@endif
</td></tr>@endforeach
</tbody></table></div>{{ $users->links() }}</section>
@endsection
