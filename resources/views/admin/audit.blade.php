@extends('layouts.app')
@section('title','Activity audit trail')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.dashboard') }}">← Monitoring dashboard</a><span class="eyebrow">ARGUS · SECURITY & OPERATIONS</span><h1>Audit trail</h1><p>Administrative configuration, CMS and role changes recorded by FlyHigh.</p></div></section>
<section class="section container"><div class="card table-wrap"><table><thead><tr><th>When (UTC)</th><th>Action</th><th>Actor</th><th>Subject</th></tr></thead><tbody>
@forelse($events as $event)<tr><td>{{ $event->created_at->format('d M Y H:i:s') }}</td><td>{{ $event->action }}</td><td>{{ $event->actor?->email ?? 'System' }}</td><td>{{ $event->subject ?? '—' }}</td></tr>
@empty<tr><td colspan="4">No recorded administrative events.</td></tr>@endforelse
</tbody></table></div>{{ $events->links() }}</section>
@endsection
