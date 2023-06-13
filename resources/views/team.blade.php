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
      <li><a href="{{ url('/team') }}" class="active">Our Team</a></li>
      <li><a href="{{ url('/contactus') }}">Contact Us</a></li>
      <li><a href="{{ url('/ticket') }}">Lihat ticket</a></li>
      <li id="login"><a href="{{ route('logout') }}">Logout</a></li>
    </ul>
  </header>


  <!-- Isi Disini -->
</br>
<div class="container">
  <div class="row">
    <div class="col-md-4">
      <div class="team-member">
        <img src="foto\teamc.jpg" alt="Team Member 1" height="300">
        <h4>Kenny Adam - (00000072683)</h4>
        <p>CEO</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="team-member">
        <img src="foto\teamf.png" alt="Team Member 2" style="object-fit: cover; width: 300px; height: 300px;">
        <h4>Febianus Felix W - (00000072737)</h4>
        <p>CTO</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="team-member">
        <img src="foto\teamd.jpg" alt="Team Member 3" height="300">
        <h4>Radinald Mirza M - (00000072087)</h4>
        <p>COO</p>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="team-member">
        <img src="foto\teame.jpg" alt="Team Member 5" height="300">
        <h4>Agra Cipta Kusuma - (00000071933)</h4>
        <p>Finance Manager</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="team-member">
        <img src="foto\teama.jpg" alt="Team Member 4" height="300">
        <h4>Justin Stephen - (00000072126)</h4>
        <p>Marketing Manager</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="team-member">
        <img src="foto\teamb.jpg" alt="Team Member 6" height="300">
        <h4>M. Rafi Akbar - (00000073818)</h4>
        <p>HR Manager</p>
      </div>
    </div>
  </div>
</div>

<style>
  .container {
    display: flex;
    flex-wrap: wrap;
  }

  .row {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
  }

  .col-md-4 {
    width: 33.33%;
    padding: 10px;
    box-sizing: border-box;
  }

  .team-member {
    text-align: center;
  }
</style>

  @include('includes.footer')
</body>
</html>