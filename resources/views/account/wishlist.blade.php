@extends('layouts.app')
@section('title','Penerbangan tersimpan')
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">YOUR WISHLIST</span><h1>Penerbangan tersimpan</h1><p>Jadwal yang Anda tandai. Ketersediaan tetap perlu diperiksa sebelum booking.</p></div></section>
<section class="section container"><div class="flight-grid">@forelse($flights as $flight)@include('components.flight-card',['flight'=>$flight])@empty<div class="empty-state wide"><h2>Belum ada penerbangan tersimpan.</h2><a class="btn btn-primary" href="{{ route('flights.index') }}">Jelajahi penerbangan</a></div>@endforelse</div>{{ $flights->links() }}</section>
@endsection
