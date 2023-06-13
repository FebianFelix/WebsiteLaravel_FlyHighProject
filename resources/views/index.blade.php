<?php

use Illuminate\Support\Facades\Session;

?>

<!DOCTYPE html>
<html>
@include('includes.head')
<body>
<!-- Header -->
<header>
    <ul>
        <li><a href="{{ url('/') }}"><img src="{{ asset('foto/logoflyhigh.png') }}" alt="logo"></a></li>
        <li><a onclick="tampilkanAlert()">Destination</a>
            <script>
                function tampilkanAlert() {
                    alert("Anda Perlu Login Untuk Dapat Mengakses Halaman Ini.");
                }
            </script></li>
        <li><a onclick="tampilkanAlert()">Journey</a></li>
        <li><a onclick="tampilkanAlert()">Pemesanan Tiket</a></li>
        <li><a onclick="tampilkanAlert()">Our Team</a></li>
        <li><a onclick="tampilkanAlert()">Contact Us</a></li>
        <li><a onclick="tampilkanAlert()">Lihat ticket</a></li>
        <li><a href="{{ url('/register') }}">Register</a></li>
        <li id="login"><a href="{{ url('/halamanLogin') }}">Login</a></li>
    </ul>
</header>
<!-- End Of Header -->

<!-- Bagian Isi/body -->
<div class="container">
    <h3 class="judul1">Welcome to Fly High</h3>
</div>

<!-- Ini Buat Naroh Slide -->
<div class="slides">
    <div class="slide">
        <img src="{{ asset('foto/slide3.png') }}" alt="slide1">
    </div>
    <div class="slide">
        <img src="{{ asset('foto/slide2.png') }}" alt="slide2">
    </div>
    <div class="slide">
        <img src="{{ asset('foto/slide1.png') }}" alt="slide3">
    </div>
    <div class="navigation">
        <a class="prev" onclick="plusSlide(-1)">&#10094;</a>
        <a class="next" onclick="plusSlide(-1)">&#10095;</a>
    </div>
</div>
    <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlide(n) {
            showSlides(slideIndex += n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("slide");
            if (n > slides.length) 
            {
                slideIndex = 1;
            }
            if (n < 1)
            {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++)
            {
                slides[i].style.display = "none";
            }
            slides[slideIndex-1].style.display = "block";
        }
    </script>
    <!--Slidenya sudah beres-->

    <div class="bodysection">
        <h1 class="judul1">Jelajahi dunia lagi yuk! 🌎</h1>
        <h4 class="judul2">Terbang ke destinasi yang telah dibuka kembali</h4>
        <div class="pembungkus">
            <div class="satu">
                <a onclick="tampilkanAlert()"><img class="gambars" src="foto\bali.png" alt="Foto bali"></a>
            </div>
            <div class="dua">
                <a onclick="tampilkanAlert()"><img class="gambars" src="foto\singapore.png" alt="Foto singapore"></a>
            </div>
            <div class="tiga">
                <a onclick="tampilkanAlert()"><img class="gambars" src="foto\thailand.png" alt="Foto thailand"></a>
            </div>
        </div>
    </div>
    <div class="jp">
        <h1 class="judul3">Panduan Wisata Anda 🗺️</h1>
        <h4 class="judul4">Kami Siap Memandu Wisata Anda</h4>
        <div class="bungkus">
            <div class="satu">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\balihay.png" alt="Foto balihay"></a>
            </div>
            <div class="dua">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\papua.png" alt="Foto papua"></a>
            </div>
            <div class="tiga">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\magelang.png" alt="Foto magelang"></a>
            </div>
            <div class="tiga">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\lombok.png" alt="Foto lombok"></a>
            </div>
        </div>
        <div class="bungkus">
            <div class="satu">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\magelang.png" alt="Foto magelang"></a>
            </div>
            <div class="dua">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\lombok.png" alt="Foto lombok"></a>
            </div>
            <div class="tiga">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\balihay.png" alt="Foto bali"></a>
            </div>
            <div class="tiga">
                <a onclick="tampilkanAlert()"><img class="gambarwisata" src="foto\papua.png" alt="Foto papua"></a>
            </div>
        </div>
        <div>
            <h1 class="judul69">Kami Siap Memandu Wisata Anda</h1>
            <h4 class="judul70">Selamat datang di layanan kami, kami adalah tim yang siap memandu perjalanan Anda dengan profesional dan ramah. Kami menyediakan berbagai jenis layanan perjalanan, mulai dari wisata budaya, petualangan alam, hingga perjalanan bisnis. Kami memiliki pengalaman dalam menyusun itinerary yang cocok dengan kebutuhan Anda. Kami juga dapat memberikan rekomendasi tempat wisata terbaik dan restoran yang populer di daerah yang akan Anda kunjungi. Selain itu, kami juga dapat membantu dalam mengatur akomodasi, transportasi, tiket, dan keperluan lainnya. Dalam perjalanan, kami akan memastikan bahwa Anda merasa aman dan nyaman. Tim kami terdiri dari pemandu wisata berpengalaman dan terlatih, sehingga Anda dapat menikmati perjalanan tanpa khawatir tentang masalah keamanan. Dengan layanan kami, Anda dapat merencanakan perjalanan Anda dengan mudah dan efisien. Kami siap membantu Anda dalam setiap langkah perjalanan, sehingga Anda dapat menikmati liburan tanpa stres dan repot. Hubungi kami sekarang untuk memulai perjalanan Anda yang tak terlupakan!</h4> 
        </div>
    </div>
    <div class="bodysection">
        <h1 class="judul1">Pemesanan Tiket 📢</h1>
        <h4 class="judul2">Dapatkan Penawaran Menarik Dari Kami!</h4>
        <div class="pembungkus">
            <div class="satu">
                <a onclick="tampilkanAlert()"><img class="puki" src="foto\man.png" alt="Foto bali"></a>
            </div>
            <div class="dua">
                <a onclick="tampilkanAlert()"><img class="puki" src="foto\iklan.png" alt="Foto singapore"></a>
            </div>
            <div class="tiga">
                <a onclick="tampilkanAlert()"><img class="puki" src="foto\cmon.png" alt="Foto thailand"></a>
            </div>
        </div>
        <div class="after">
            <div class="satu">
                <a onclick="tampilkanAlert()"><img class="puki" src="foto\foto1.png" alt="Foto bali"></a>
            </div>
            <div class="dua">
                <a onclick="tampilkanAlert()"><img class="puki" src="foto\foto2.png" alt="Foto singapore"></a>
            </div>
            <div class="tiga">
                <a onclick="tampilkanAlert()"><img class="puki" src="foto\foto3.png" alt="Foto thailand"></a>
            </div>
        </div>
        <div>
            <h1 class="judul111">Dapatkan Penawaran Menarik Dari Kami!</h1>
            <h4 class="judul222">Dapatkan penawaran menarik dari kami! Kami menawarkan diskon besar-besaran, promo khusus, dan hadiah menarik untuk Anda. Jangan lewatkan kesempatan emas ini untuk mendapatkan perjalanan berkualitas dengan harga yang lebih terjangkau. Temukan berbagai macam produk yang kami tawarkan, dari hotel, tiket, tempat wisata, dan banyak lagi. Kami selalu berusaha memberikan yang terbaik untuk pelanggan kami dengan produk-produk berkualitas dan harga yang terjangkau. Beralihlah ke kami dan nikmati pengalaman liburan yang menyenangkan dengan penawaran menarik dari kami. Tunggu apa lagi? Buruan checkout sekarang!</h4>
        </div>
    </div>
    <div class="jpp">
        <h1 class="judul1">Contact Us ☎️</h1>
        <h4 class="judul2">Customer Service Kami Siap Melayani Anda 24/7</h4>
        <div class="bungkuss">
            <div class="satu">
                <img class="liat" src="foto\phone.png" alt="Foto telpon">
                <h4 class="kece">Phone Number</h4>
                <p class="p">08128219769</p>
            </div>
            <div class="dua">
                <img class="liat" src="foto\email.png" alt="Foto email">
                <h4 class="kece">Email</h4>
                <p class="p">fly.high@gmail.com</p>
            </div>
            <div class="tiga">
                <img class="liat" src="foto\alamat.png" alt="Foto alamat">
                <h4 class="kece">Address</h4>
                <p class="p">Serpong, South Tangerang</p>
            </div>
        </div>
        <!-- Peta -->
        <div class="lepas">
            <div class="ah">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3496.061602497308!2d106.61268609999999!3d-6.2650289!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fd080e0ad6bb%3A0xc582fd9207889ec6!2sOYO%20120%20GP%20Residence!5e1!3m2!1sid!2sid!4v1683899201683!5m2!1sid!2sid" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            
            </div>
            <div class="ahh">
                <div class="pew">
                    <div class="dalem">
                        <p class="judul1">Address</p>
                        <p class="judul2222">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                    </div>
                </div>
            </div>
        </div>
    <!-- End of peta -->
        <div>
            <h1 class="judul1111">Hubungi Kami Sekarang!</h1>
            <h4 class="judul2222">Anda memiliki pertanyaan, saran, atau masukan untuk kami? Kami senang mendengarnya! Kami selalu siap membantu dan memberikan jawaban terbaik untuk kebutuhan Anda. Untuk menghubungi kami, Anda dapat mengunjungi website kami dan mengisi formulir kontak yang tersedia. Tim kami akan segera merespon setiap pertanyaan yang Anda ajukan. Anda juga dapat menghubungi kami melalui email atau telepon yang tertera pada website kami. Kami akan merespon dengan cepat dan memberikan solusi terbaik untuk setiap masalah yang Anda hadapi. Kami sangat menghargai setiap masukan dan saran dari pelanggan kami, karena itu kami selalu berusaha untuk meningkatkan kualitas pelayanan kami. Jangan ragu untuk menghubungi kami jika Anda membutuhkan bantuan atau informasi lebih lanjut. Terima kasih telah mempercayakan kebutuhan Anda pada kami. Kami siap membantu dan melayani Anda dengan sepenuh hati.</h4>
        </div>
    </div>

    @include('includes.footer')

    </body>
</html>