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
        <!-- ... -->

        <!-- Tujuan -->
        <!-- ... -->

        <!-- Maskapai Penerbangan -->
        <!-- ... -->

        <div class="form-group">
            <label for="jumlah_penumpang">Jumlah Penumpang:</label>
            <input type="number" name="jumlah_penumpang" id="jumlah_penumpang" min="1" required>
        </div>

        <!-- Tanggal Pergi -->
        <!-- ... -->

        <!-- Tanggal Pulang -->
        <!-- ... -->

        <!-- Kelas Penerbangan -->
        <!-- ... -->

        <!-- Metode Pembayaran -->
        <!-- ... -->

        <div class="form-group">
            <button type="submit" name="submit" value="Submit">Proses Data</button>
            <button type="reset" name="reset" value="reset">Reset</button>
        </div>
    </form>
    </div>

    @include('includes.footer')

    <script>
        document.querySelector('button[name="submit"]').addEventListener('click', function(event) {
            event.preventDefault();
            alert("Anda akan diarahkan ke halaman pembayaran.");
            // Lakukan logika penyimpanan data ke database di sini

            // Redirect ke halaman pembayaran
            window.location.href = "{{ url('/pembayaran') }}";
        });
    </script>
</body>
</html>
