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
                <li><a href="{{ url('/pemesanan') }}">Pemesanan Tiket</a></li>
                <li><a href="{{ url('/team') }}">Our Team</a></li>
                <li><a href="{{ url('/contactus') }}"class="active">Contact Us</a></li>
                <li><a href="{{ url('/ticket') }}">Lihat ticket</a></li>
                <li id="login"><a href="{{ route('logout') }}">Logout</a></li>
             </ul>
        </header>
    <!-- End Of Header -->  
    
    <!-- Bagian Isi/body -->
    <div class="jpp">
        <h1 class="judul1">Contact Us ☎️</h1>
        <h4 class="judul2">Customer Service Kami Siap Melayani Anda 24/7</h4>
        <div class="bungkuss">
            <div class="satu">
                <img class="liat" src="foto\phone.png" alt="Foto telpon">
                <h4 class="kece">Phone Number</h4>
                <p class="p">(021-380800)</p>
            </div>
            <div class="dua">
                <img class="liat" src="foto\email.png" alt="Foto email">
                <h4 class="kece">Email</h4>
                <p class="p">customerservice@flyhigh.com</p>
            </div>
            <div class="tiga">
                <img class="liat" src="foto\alamat.png" alt="Foto alamat">
                <h4 class="kece">Address</h4>
                <p class="p">Indonesia, Tangerang Selatan, Banten</p>
            </div>
        </div>
        <!-- Peta -->
            <div class="katakanpeta">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3496.061602497308!2d106.61268609999999!3d-6.2650289!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fd080e0ad6bb%3A0xc582fd9207889ec6!2sOYO%20120%20GP%20Residence!5e1!3m2!1sid!2sid!4v1683899201683!5m2!1sid!2sid" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>            
            </div>
    <!-- End of peta -->
        <div>
            <h1 class="judul1111">Hubungi Kami Sekarang!</h1>
            <h4 class="judul2222">Anda memiliki pertanyaan, saran, atau masukan untuk kami? Kami senang mendengarnya! Kami selalu siap membantu dan memberikan jawaban terbaik untuk kebutuhan Anda. Untuk menghubungi kami, Anda dapat mengunjungi website kami dan mengisi formulir kontak yang tersedia. Tim kami akan segera merespon setiap pertanyaan yang Anda ajukan. Anda juga dapat menghubungi kami melalui email atau telepon yang tertera pada website kami. Kami akan merespon dengan cepat dan memberikan solusi terbaik untuk setiap masalah yang Anda hadapi. Kami sangat menghargai setiap masukan dan saran dari pelanggan kami, karena itu kami selalu berusaha untuk meningkatkan kualitas pelayanan kami. Jangan ragu untuk menghubungi kami jika Anda membutuhkan bantuan atau informasi lebih lanjut. Terima kasih telah mempercayakan kebutuhan Anda pada kami. Kami siap membantu dan melayani Anda dengan sepenuh hati.</h4>
        </div>
    </div>
    <!--End Of Body-->

    @include('includes.footer')

    </body>
</html>