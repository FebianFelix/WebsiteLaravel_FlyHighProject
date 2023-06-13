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
                <li><a href="{{ url('/destination') }}"class="active">Destination</a></li>
                <li><a href="{{ url('/journey') }}">Journey</a></li>
                <li><a href="{{ url('/pemesanan') }}">Pemesanan Tiket</a></li>
                <li><a href="{{ url('/team') }}">Our Team</a></li>
                <li><a href="{{ url('/contactus') }}">Contact Us</a></li>
                <li><a href="{{ url('/ticket') }}">Lihat ticket</a></li>
                <li id="login"><a href="{{ route('logout') }}">Logout</a></li>
             </ul>
        </header>
    <!-- End Of Header -->  
    
    <!-- Bagian Isi/body -->

    <div class="bodysection">
        <h1 class="judul1">Jelajahi dunia lagi yuk! 🌎</h1>
        <h4 class="judul2">Terbang ke destinasi yang telah dibuka kembali</h4>
        <div class="pembungkus">
            <div class="satu">
            <a href="{{ url('/bali') }}"><img class="gambars" src="foto\bali.png" alt="Foto bali"></a>
            </div>
            <div class="dua">
            <a href="{{ url('/singapore') }}"><img class="gambars" src="foto\singapore.png" alt="Foto singapore"></a>
            </div>
            <div class="tiga">
            <a href="{{ url('/thailand') }}"><img class="gambars" src="foto\thailand.png" alt="Foto thailand"></a>
            </div>
        </div>
    </div>

    @include('includes.footer')

    </body>
</html>