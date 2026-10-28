@extends('layouts.app')
@php($editing = $flight->exists)
@section('title', $editing ? 'Edit penerbangan' : 'Tambah penerbangan')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.flights.index') }}">← Kelola penerbangan</a><span class="eyebrow">FLIGHT MANAGEMENT</span><h1>{{ $editing ? 'Edit penerbangan' : 'Tambah penerbangan' }}</h1><p>Informasi di bawah digunakan sebagai data simulasi.</p></div></section>
<section class="section container"><div class="card form-card admin-form"><form method="POST" action="{{ $editing ? route('admin.flights.update', $flight) : route('admin.flights.store') }}" class="stack-form">@csrf @if($editing)@method('PUT')@endif
    <div class="form-grid"><label>Nomor penerbangan<input name="flight_number" maxlength="20" required value="{{ old('flight_number', $flight->flight_number) }}" placeholder="FH102"></label>
        <label>Maskapai<input name="airline" maxlength="100" required value="{{ old('airline', $flight->airline) }}" placeholder="FlyHigh Air"></label>
        <label>Kota asal<input name="origin" maxlength="100" required value="{{ old('origin', $flight->origin) }}" placeholder="Jakarta"></label>
        <label>Kota tujuan<input name="destination" maxlength="100" required value="{{ old('destination', $flight->destination) }}" placeholder="Bali"></label>
        <label>Kode bandara asal (IATA)<input name="origin_code" maxlength="3" pattern="[A-Za-z]{3}" value="{{ old('origin_code', $flight->origin_code) }}" placeholder="CGK"></label>
        <label>Kode bandara tujuan (IATA)<input name="destination_code" maxlength="3" pattern="[A-Za-z]{3}" value="{{ old('destination_code', $flight->destination_code) }}" placeholder="DPS"></label>
        <label>Keberangkatan (UTC)<input type="datetime-local" name="departure_at" required value="{{ old('departure_at', $flight->departure_at?->format('Y-m-d\TH:i')) }}"></label>
        <label>Kedatangan (UTC)<input type="datetime-local" name="arrival_at" required value="{{ old('arrival_at', $flight->arrival_at?->format('Y-m-d\TH:i')) }}"></label>
        <label>Harga (Rp)<input type="number" name="price" min="1000" max="1000000000" required value="{{ old('price', $flight->price) }}"></label>
        <label>Total kursi<input type="number" name="seats_total" min="1" max="1000" required value="{{ old('seats_total', $flight->seats_total) }}"></label>
        <label>Kelas kabin<select name="cabin_class">@foreach(['economy'=>'Economy','premium_economy'=>'Premium Economy','business'=>'Business','first'=>'First'] as $value=>$label)<option value="{{ $value }}" {{ old('cabin_class',$flight->cabin_class??'economy')===$value?'selected':'' }}>{{ $label }}</option>@endforeach</select></label>
        <label>Kabin baggage (kg)<input type="number" min="0" max="50" name="carry_on_kg" value="{{ old('carry_on_kg',$flight->carry_on_kg??7) }}"></label>
        <label>Checked baggage (kg)<input type="number" min="0" max="100" name="checked_baggage_kg" value="{{ old('checked_baggage_kg',$flight->checked_baggage_kg??20) }}"></label>
        <label>Jenis pesawat<input name="aircraft_type" maxlength="80" value="{{ old('aircraft_type',$flight->aircraft_type) }}" placeholder="Airbus A320"></label>
    </div>
    <label>Ringkasan syarat tarif<textarea name="fare_rules" maxlength="500" rows="4">{{ old('fare_rules',$flight->fare_rules) }}</textarea></label>
    <label class="check-label"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $editing ? $flight->is_active : true) ? 'checked' : '' }}><span>Jadwal aktif</span></label>
    <div class="form-actions"><a class="btn btn-ghost" href="{{ route('admin.flights.index') }}">Batal</a><button class="btn btn-primary" type="submit">{{ $editing ? 'Simpan perubahan' : 'Buat penerbangan' }} →</button></div>
</form></div></section>
@endsection
