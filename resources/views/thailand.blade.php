<!DOCTYPE html>
<html lang="en">
@include('includes.head')
<body>
    <div class="container">
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
    <main>
    <div class="row">
        <div class="col-md-6">
            <div class="image-container">
            <img class="gambars" src="foto\thailand.png" alt="Foto thailand">
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-container">
            <p>
                                    Thailand is a country located in Southeast Asia, known for its rich cultural heritage, stunning landscapes, and vibrant cities. With a blend of ancient traditions and modern attractions, Thailand offers a unique travel experience for visitors from around the world.
                                </p>
                                <p>
                                    One of the highlights of Thailand is its diverse range of landscapes. From the pristine beaches of Phuket and Krabi to the lush mountains of Chiang Mai and the bustling streets of Bangkok, there is something for everyone. Visitors can relax on the white sandy beaches, explore the tropical rainforests, or immerse themselves in the vibrant city life.
                                </p>
                                <p>
                                    Thailand is also famous for its ancient temples and historical sites. The iconic Grand Palace in Bangkok is a must-visit, with its intricate architecture and rich history. Other notable temples include Wat Arun, Wat Phra That Doi Suthep, and Wat Phra Kaew. These temples offer a glimpse into Thailand's spiritual and cultural heritage.
                                </p>
                                <p>
                                    Whether you're exploring ancient temples, relaxing on pristine beaches, or savoring the flavors of Thai cuisine, Thailand provides a captivating travel experience. Its warm hospitality, rich history, and natural beauty make it an unforgettable destination. Plan your trip to Thailand and embark on a journey of discovery and adventure.
                                </p>
            </div>
        </div>
    </div>
</main>

        <footer class="text-center mt-5">
            <p>&copy; 2023 Thailand Tourism. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    @include('includes.footer')
    <style>
        .row {
            display: flex;
            align-items: center;
        }

        .col-md-6 {
            width: 50%;
        }

        .image-container {
            text-align: center;
        }

        .text-container {
            padding: 0 20px;
            text-align: justify;
        }

        .gambars {
            max-width: 100%;
            height: auto;
        }
    </style>

</body>
</html>