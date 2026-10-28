@extends('layouts.app')
@section('title', 'Buat reservasi')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('flights.show', $flight) }}">← Detail penerbangan</a><span class="eyebrow">BOOK YOUR FLIGHT</span><h1>Detail penumpang</h1><p>Lengkapi nama untuk membuat reservasi demo.</p></div></section>
<section class="section container two-column"><div class="card form-card"><h2>Informasi penumpang</h2><p class="muted">Masukkan nama lengkap. Maksimal 6 penumpang per reservasi.</p>
    <form method="POST" action="{{ route('bookings.store') }}" class="stack-form">@csrf<input type="hidden" name="flight_id" value="{{ $flight->id }}"><input type="hidden" name="request_key" value="{{ old('request_key', $requestKey) }}">
        <div id="passenger-list">
            @foreach(old('passengers', ['']) as $index => $name)
                <label class="passenger-field">Penumpang {{ $index + 1 }}<input name="passengers[]" required maxlength="120" value="{{ $name }}" placeholder="Nama lengkap sesuai identitas"></label>
            @endforeach
        </div>
        <button type="button" id="add-passenger" class="btn btn-outline btn-small">+ Tambah penumpang</button>
        <p class="muted">Jumlah akhir dan ketersediaan kursi divalidasi ulang oleh server.</p>
        <button type="submit" class="btn btn-primary btn-block">Konfirmasi reservasi demo →</button>
    </form>
</div>
<aside class="card summary-card"><span class="eyebrow">YOUR FLIGHT</span><h2>{{ $flight->origin }} → {{ $flight->destination }}</h2><p>{{ $flight->airline }} · {{ $flight->flight_number }}</p><div class="divider"></div><div class="summary-line"><span>Keberangkatan</span><strong>{{ $flight->departure_at->format('d M Y, H:i') }}</strong></div><div class="summary-line"><span>Harga / orang</span><strong>Rp {{ number_format($flight->price, 0, ',', '.') }}</strong></div><div class="summary-line"><span>Kursi tersedia</span><strong>{{ $flight->availableSeats() }}</strong></div><div class="divider"></div><p class="muted">Tidak ada pembayaran yang diproses. Ini hanya prototype portfolio.</p></aside>
</section>
<script>
(function () {
    const list = document.getElementById('passenger-list');
    const button = document.getElementById('add-passenger');
    button.addEventListener('click', function () {
        const count = list.querySelectorAll('input').length;
        if (count >= 6) return;
        const label = document.createElement('label');
        label.className = 'passenger-field';
        label.textContent = 'Penumpang ' + (count + 1);
        const input = document.createElement('input');
        input.name = 'passengers[]';
        input.required = true;
        input.maxLength = 120;
        input.placeholder = 'Nama lengkap sesuai identitas';
        label.appendChild(input);
        list.appendChild(label);
        button.disabled = count + 1 >= 6;
    });
})();
</script>
@endsection
