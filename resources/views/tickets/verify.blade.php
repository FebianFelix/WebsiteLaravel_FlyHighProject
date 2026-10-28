@extends('layouts.app')
@section('title', 'Verifikasi reservasi demo')
@section('content')
<section class="auth-wrap"><div class="auth-card"><span class="eyebrow">FLYHIGH · DOCUMENT VERIFICATION</span><h1>Status reservasi.</h1><p>Verifikasi ini hanya untuk dokumentasi simulasi FlyHigh.</p>
<div class="summary-line"><span>Reference</span><strong>{{ $reference }}</strong></div>
<div class="summary-line"><span>Route</span><strong>{{ $routeLabel }}</strong></div>
<div class="summary-line"><span>Status</span><strong>{{ ucfirst($status) }} (demo)</strong></div>
<div class="alert alert-error">Dokumen ini bukan tiket maskapai asli dan tidak dapat digunakan untuk perjalanan.</div>
<a class="btn btn-primary btn-block" href="{{ route('home') }}">Kembali ke FlyHigh</a>
</div></section>
@endsection
