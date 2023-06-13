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
                <li><a href="{{ url('/journey') }}"class="active">Journey</a></li>
                <li><a href="{{ url('/pemesanan') }}">Pemesanan Tiket</a></li>
                <li><a href="{{ url('/team') }}">Our Team</a></li>
                <li><a href="{{ url('/contactus') }}">Contact Us</a></li>
                <li><a href="{{ url('/ticket') }}">Lihat ticket</a></li>
                <li id="login"><a href="{{ route('logout') }}">Logout</a></li>
             </ul>
        </header>
    <!-- End Of Header -->  
    
    <!-- Bagian Isi/body -->
    <div class="jpgas">
        <h1 class="judul1">Panduan Wisata Anda 🗺️</h1>
        <h4 class="judul2">Kami Siap Memandu Wisata Anda</h4>
        <div class="pembungkus">
            <div class="satu">
                <img class="gambarwisata" src="foto\balihay.png" alt="Foto balihay">
            </div>
            <div class="dua">
                <img class="gambarwisata" src="foto\papua.png" alt="Foto papua">
            </div>
            <div class="tiga">
                <img class="gambarwisata" src="foto\magelang.png" alt="Foto magelang">
            </div>
            <div class="tiga">
                <img class="gambarwisata" src="foto\lombok.png" alt="Foto lombok">
            </div>
        </div>
        <div class="pembungkus">
            <div class="satu">
                <img class="gambarwisata" src="foto\magelang.png" alt="Foto Magelang">
            </div>
            <div class="dua">
                <img class="gambarwisata" src="foto\lombok.png" alt="Foto Lombok">
            </div>
            <div class="tiga">
                <img class="gambarwisata" src="foto\balihay.png" alt="Foto Balihay">
            </div>
            <div class="tiga">
                <img class="gambarwisata" src="foto\papua.png" alt="Foto Papua">
            </div>
        </div>
        <div class="pembungkus">
            <div class="satu">
                <img class="gambarwisata" src="foto\papua.png" alt="Foto Papua">
            </div>
            <div class="dua">
                <img class="gambarwisata" src="foto\magelang.png" alt="Foto magelang">
            </div>
            <div class="tiga">
                <img class="gambarwisata" src="foto\lombok.png" alt="Foto lombok">
            </div>
            <div class="tiga">
                <img class="gambarwisata" src="foto\balihay.png" alt="Foto balihay">
            </div>
        </div>
        <div>
            <h1 class="judul11">Kami Siap Memandu Wisata Anda</h1>
            <h4 class="judul22">Selamat datang di layanan kami, kami adalah tim yang siap memandu perjalanan Anda dengan profesional dan ramah. Kami menyediakan berbagai jenis layanan perjalanan, mulai dari wisata budaya, petualangan alam, hingga perjalanan bisnis. Kami memiliki pengalaman dalam menyusun itinerary yang cocok dengan kebutuhan Anda. Kami juga dapat memberikan rekomendasi tempat wisata terbaik dan restoran yang populer di daerah yang akan Anda kunjungi. Selain itu, kami juga dapat membantu dalam mengatur akomodasi, transportasi, tiket, dan keperluan lainnya. Dalam perjalanan, kami akan memastikan bahwa Anda merasa aman dan nyaman. Tim kami terdiri dari pemandu wisata berpengalaman dan terlatih, sehingga Anda dapat menikmati perjalanan tanpa khawatir tentang masalah keamanan. Dengan layanan kami, Anda dapat merencanakan perjalanan Anda dengan mudah dan efisien. Kami siap membantu Anda dalam setiap langkah perjalanan, sehingga Anda dapat menikmati liburan tanpa stres dan repot. Hubungi kami sekarang untuk memulai perjalanan Anda yang tak terlupakan!</h4> 
        </div>
    </div>

    @include('includes.footer')

    </body>
</html>