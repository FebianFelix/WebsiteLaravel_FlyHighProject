<?php
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

?>

<!DOCTYPE html>
<html>
@include('includes.head')
    <body>
    <!-- Header -->
    <header>
            <ul>
                <li><a href="{{ url('/indexLogin') }}"><img src="foto\logoflyhigh.png" alt="logo"></a></li>
                <li><a href="{{ url('/indexLogin') }}">Home</a></li>
                <li><a href="{{ url('/destination') }}">Destination</a></li>
                <li><a href="{{ url('/journey') }}">Journey</a></li>
                <li><a href="{{ url('/pemesanan') }}"class="active">Pemesanan Tiket</a></li>
                <li><a href="{{ url('/team') }}">Our Team</a></li>
                <li><a href="{{ url('/contactus') }}">Contact Us</a></li>
                <li><a href="{{ url('/ticket') }}">Lihat ticket</a></li>
                <li id="login"><a href="{{ route('logout') }}">Logout</a></li>
             </ul>
        </header>
    <!-- End Of Header -->  
    
    <!-- Bagian Isi/body -->
    <div class="bodysection">
        <h1 class="judul1">Pemesanan Tiket 📢</h1>
        <h4 class="judul2">Dapatkan Penawaran Menarik Dari Kami!</h4>
        <div class="pembungkus">
            <div class="satu">
                <a href="{{ url('/form') }}"><img class="puki" src="foto\man.png" alt="Foto bali"></a>
            </div>
            <div class="dua">
                <a href="{{ url('/form') }}"><img class="puki" src="foto\iklan.png" alt="Foto singapore"></a>
            </div>
            <div class="tiga">
                <a href="{{ url('/form') }}"><img class="puki" src="foto\cmon.png" alt="Foto thailand"></a>
            </div>
        </div>
        <div class="after">
            <div class="satu">
                <a href="{{ url('/form') }}"><img class="puki" src="foto\foto1.png" alt="Foto bali"></a>
            </div>
            <div class="dua">
                <a href="{{ url('/form') }}"><img class="puki" src="foto\foto2.png" alt="Foto singapore"></a>
            </div>
            <div class="tiga">
                <a href="{{ url('/form') }}"><img class="puki" src="foto\foto3.png" alt="Foto thailand"></a>
            </div>
        </div>
        <div>
            <h1 class="judul111">Dapatkan Penawaran Menarik Dari Kami!</h1>
            <h4 class="judul222">Dapatkan penawaran menarik dari kami! Kami menawarkan diskon besar-besaran, promo khusus, dan hadiah menarik untuk Anda. Jangan lewatkan kesempatan emas ini untuk mendapatkan perjalanan berkualitas dengan harga yang lebih terjangkau. Temukan berbagai macam produk yang kami tawarkan, dari hotel, tiket, tempat wisata, dan banyak lagi. Kami selalu berusaha memberikan yang terbaik untuk pelanggan kami dengan produk-produk berkualitas dan harga yang terjangkau. Beralihlah ke kami dan nikmati pengalaman liburan yang menyenangkan dengan penawaran menarik dari kami. Tunggu apa lagi? Buruan checkout sekarang!</h4>
        </div>
    </div>

    @include('includes.footer')

    </body>
</html>