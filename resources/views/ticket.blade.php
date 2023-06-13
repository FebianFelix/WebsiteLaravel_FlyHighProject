<?php
use App\Models\Ticket;
use App\Models\Penumpang;
use App\Models\Tujuan;
use App\Models\TujuanAwal;

// Retrieve all tickets from the database
$tickets = Ticket::all();

?>

<!DOCTYPE html>
<html>
@include('includes.head')
<body>
<!-- Header -->
<header>
    <ul>
        <li><a href="{{ url('/indexLogin') }}"><img src="foto\logoflyhigh.png" alt="logo"></a></li>
        <li><a href="{{ url('/indexLogin') }}" >Home</a></li>
        <li><a href="{{ url('/destination') }}">Destination</a></li>
        <li><a href="{{ url('/journey') }}">Journey</a></li>
        <li><a href="{{ url('/pemesanan') }}">Pemesanan Tiket</a></li>
        <li><a href="{{ url('/team') }}">Our Team</a></li>
        <li><a href="{{ url('/contactus') }}">Contact Us</a></li>
        <li><a href="{{ url('/ticket') }}" class="active">Lihat ticket</a></li>
        <li id="login"><a href="{{ route('logout') }}">Logout</a></li>
    </ul>
</header>
<!-- End Of Header -->  

<!-- Bagian Isi/body -->
@foreach ($tickets as $ticket)
    <?php
    $tujuan_awal = $ticket->tujuan_awal;
    $tujuan = $ticket->tujuan;
    $jumlah_penumpang = $ticket->jumlah_penumpang;
    $penumpang_id = $ticket->penumpang_id;

    // Retrieve the associated penumpang record
    $penumpang = Penumpang::find($penumpang_id);

    // Check if the penumpang record exists
    if ($penumpang) {
        $nama_penumpang = explode(',', $penumpang->penumpang_name);
    } else {
        $nama_penumpang = [];
    }
        // Retrieve the tujuan record
        $tujuan_record = DB::table('tbl_tujuan')->where('tujuan_id', $ticket->tujuan_id)->first();
        $tujuan = $tujuan_record ? $tujuan_record->tujuan_name : '';

        // Retrieve the tujuan_awal record
        $tujuan_awal_record = DB::table('tbl_tujuan_awal')->where('tujuan_awal_id', $ticket->tujuan_awal_id)->first();
        $tujuan_awal = $tujuan_awal_record ? $tujuan_awal_record->tujuan_awal_name : '';

        // Retrieve other ticket properties
        $tanggal_pergi = $penumpang ? $penumpang->tanggal_pergi : '';
        $tanggal_pulang = $penumpang ? $penumpang->tanggal_pulang : '';
        $kelas_penerbangan = $ticket->kelas_id;
        $maskapai = $ticket->maskapai_id;
        $kategori = $ticket->kategori_id;
        $metode_pembayaran = $ticket->metode_pembayaran_id;

    // Menghitung harga berdasarkan tujuan dan kelas penerbangan
    $harga_per_penumpang = 0;
    $harga_total = 0;

    // Harga berdasarkan tujuan
    switch ($tujuan) {
        case 'Jakarta':
            $harga_per_penumpang += 1000000;
            break;
        case 'Bali':
            $harga_per_penumpang += 1500000;
            break;
        case 'Tokyo':
            $harga_per_penumpang += 3000000;
            break;
        case 'California':
            $harga_per_penumpang += 2500000;
            break;
        case 'New York':
            $harga_per_penumpang += 2800000;
            break;
        case 'Dubai':
            $harga_per_penumpang += 2000000;
            break;
    }

    // Harga berdasarkan kelas penerbangan
    switch ($kelas_penerbangan) {
        case 'Ekonomi':
            $harga_per_penumpang *= 1;
            break;
        case 'Bisnis':
            $harga_per_penumpang *= 1.5;
            break;
        case 'First':
            $harga_per_penumpang *= 2;
            break;
    }

    // Menghitung harga total
    $harga_total = $harga_per_penumpang * $jumlah_penumpang;
    ?>

    <div class="muncul">
        <div class="munculatas">
            <div class="kiriatas"><p class="tiketmuncul">{{ $kelas_penerbangan }}</p></div>
            <div class="kiriatas"><p class="tiketmunculs">{{ $maskapai }}</p></div>
        </div>
        <div class="munculbawah">
            <div class="munculkiri">
                <img class="barcode" src="foto\barcode.png" alt="Deskripsi Gambar">
            </div>
            <div class="muncultengah">
        @if (count($nama_penumpang) > 0)
            <p class="texttengah">Name:
                @foreach ($nama_penumpang as $nama)
                    {{ $nama }}
                @endforeach
            </p>
        @else
            <p class="texttengah">No passenger information available.</p>
        @endif
                <p class="texttengah">Category: {{ $kategori }}</p>
                <p class="texttengah">Date: {{ $tanggal_pergi }} - {{ $tanggal_pulang }}</p>
                <p class="texttengah">Seat: 25D</p>
                <p class="texttengah">From: {{ $tujuan_awal }}</p>
                <p class="texttengah">To: {{ $tujuan }}</p>
            </div>
            <div class="munculkanan">
                <p class="texttengah">Name:
                    @foreach ($nama_penumpang as $nama)
                        {{ $nama }}<br>
                    @endforeach
                </p>
                <p class="texttengah">Category: {{ $kategori }}</p>
                <p class="texttengah">Date: {{ $tanggal_pergi }} - {{ $tanggal_pulang }}</p>
                <p class="texttengah">Seat: 25D</p>
                <p class="texttengah">From: {{ $tujuan_awal }}</p>
                <p class="texttengah">To: {{ $tujuan }}</p>
                <p class="textbiasa">BA/BLUE26780915 BOARDING PASS</p>
            </div>
        </div>
        <div class="bawahbanget">
            <p class="males">PLEASE BE AT THE BOARDING GATE AT LEAST 30 MINUTES BEFORE BOARDING TIME.</p>
        </div>
    </div>
@endforeach

<!-- Button untuk mencetak hasil form sebagai PDF -->
<button class="pdf" onclick="printPDF()">Export PDF</button>

<!-- Script untuk mencetak hasil form sebagai PDF -->
<script>
    function printPDF() {
        // Menggunakan window.print() untuk mencetak halaman
        window.print();
    }
</script>

@include('includes.footer')

</body>
</html>
