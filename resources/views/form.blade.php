<?php
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

?>

<!DOCTYPE html>
<html lang="id">
@include('includes.head')
<header>
    <ul>
        <li><a href="{{ url('/indexLogin') }}"><img src="foto\logoflyhigh.png" alt="logo"></a></li>
        <li><a href="{{ url('/indexLogin') }}">Home</a></li>
        <li><a href="{{ url('/destination') }}">Destination</a></li>
        <li><a href="{{ url('/journey') }}">Journey</a></li>
        <li><a href="{{ url('/pemesanan') }}" class="active">Pemesanan Tiket</a></li>
        <li><a href="{{ url('/team') }}">Our Team</a></li>
        <li><a href="{{ url('/contactus') }}">Contact Us</a></li>
        <li><a href="{{ url('/ticket') }}">Lihat ticket</a></li>
        <li id="login"><a href="{{ route('logout') }}">Logout</a></li>
    </ul>
</header>

<body>
    <h1 class="judul1">Form Pemesanan Tiket Pesawat</h1>
    <div class="jangan">
    <form method="POST" action="{{ route('store.ticket') }}">
        @csrf
            @csrf <!-- Tambahkan ini untuk melindungi form dari serangan CSRF -->

            <div class="form-group">
                <label for="nama_penumpang">Nama Penumpang:</label>
                <input type="text" name="nama_penumpang[]" id="nama_penumpang" required>
            </div>


<!-- Kategori Penumpang -->
<div class="form-group">
    <label for="kategori">Kategori Penumpang:</label>
    <select name="kategori" id="kategori" required>
        <option value="">- Pilih Kategori -</option>
        @isset($kategori)
            @foreach($kategori as $item)
                <option value="{{ $item->kategori_id }}">{{ $item->kategori_name }}</option>
            @endforeach
        @endisset
    </select>
</div>


<!-- Keberangkatan -->
<div class="form-group">
    <label for="tujuan_awal">Keberangkatan:</label>
    <select name="tujuan_awal" id="tujuan_awal" required>
        <option value="">- Pilih Keberangkatan -</option>
        @foreach($tujuanAwal as $item)
            <option value="{{ $item->tujuan_awal_id }}">{{ $item->tujuan_awal_name }}</option>
        @endforeach
    </select>
</div>


<!-- Tujuan -->
<div class="form-group">
    <label for="tujuan">Tujuan:</label>
    <select name="tujuan" id="tujuan" required>
        <option value="">- Pilih Tujuan -</option>
        @foreach($tujuan as $item)
            <option value="{{ $item->tujuan_id }}">{{ $item->tujuan_name }}</option>
        @endforeach
    </select>
</div>


<!-- Maskapai Penerbangan -->
<div class="form-group">
    <label for="maskapai">Pilih Maskapai Penerbangan:</label>
    <select name="maskapai" id="maskapai" required>
        <option value="">- Pilih Maskapai Penerbangan -</option>
        @foreach($maskapai as $item)
            <option value="{{ $item->maskapai_id }}">{{ $item->maskapai_name }}</option>
        @endforeach
    </select>
</div>

            <div class="form-group">
                <label for="jumlah_penumpang">Jumlah Penumpang:</label>
                <input type="number" name="jumlah_penumpang" id="jumlah_penumpang" min="1" required>
            </div>

            <div class="form-group">
                <label for="tanggal_pergi">Tanggal Pergi:</label>
                <input type="date" name="tanggal_pergi" id="tanggal_pergi" required>
            </div>

            <div class="form-group">
                <label for="tanggal_pulang">Tanggal Pulang:</label>
                <input type="date" name="tanggal_pulang" id="tanggal_pulang" required>
            </div>

            <!-- Kelas Penerbangan -->
            <div class="form-group">
    <label for="kelas_penerbangan">Kelas Penerbangan:</label>
    <select name="kelas_penerbangan" id="kelas_penerbangan" required>
        <option value="">- Pilih Kelas Penerbangan -</option>
        @foreach($kelasPenerbangan as $kelas)
            <option value="{{ $kelas->kelas_id }}">{{ $kelas->kelas_name }}</option>
        @endforeach
    </select>
</div>

            <!-- Metode Pembayaran -->
            <div class="form-group">
    <label for="metode_pembayaran">Metode Pembayaran:</label>
    <select name="metode_pembayaran" id="metode_pembayaran" required>
        <option value="">- Pilih Metode Pembayaran -</option>
        @foreach($metodePembayaran as $metode)
            <option value="{{ $metode->id }}">{{ $metode->metode_pembayaran_name }}</option>
        @endforeach
    </select>
</div>
            <div class="form-group">
                <button type="submit" name="submit" value="Submit">Proses Data</button>
                <button type="reset" name="reset" value="reset">Reset</button>
            </div>
        </form>
    </div>

    @include('includes.footer')

</body>

</html>
