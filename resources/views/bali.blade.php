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
                <img class="gambars img-fluid" src="foto\bali.png" alt="Foto Bali">
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-container">
                <p>
                    Bali is an island and province in Indonesia, located in the westernmost end of the Lesser Sunda
                    Islands. It is known for its stunning natural beauty, vibrant culture, and warm hospitality. Bali offers
                    a diverse range of experiences, from pristine beaches and lush rice terraces to ancient temples and
                    traditional arts. It has become a popular destination for tourists from around the world, attracting
                    millions of visitors each year.
                </p>
                <p>
                    One of the highlights of Bali is its beautiful beaches. From the famous Kuta Beach with its vibrant
                    nightlife to the tranquil shores of Nusa Dua, there is a beach for every preference. Visitors can relax
                    on the sandy shores, go surfing, or enjoy various water activities such as snorkeling and diving. Bali's
                    beaches are also known for their breathtaking sunsets, providing a perfect backdrop for romantic moments.
                </p>
                <p>
                    Bali is also a paradise for food lovers. The island offers a wide range of culinary delights, from local
                    Balinese dishes to international cuisine. Visitors can sample traditional dishes such as nasi goreng
                    (fried rice), satay (grilled skewered meat), and babi guling (suckling pig). There are also numerous
                    restaurants and cafes serving international dishes, catering to different tastes and preferences.
                </p>
                <p>
                    Whether you're seeking relaxation, adventure, or cultural exploration, Bali has something for everyone. Its
                    unique blend of natural beauty, cultural heritage, and warm hospitality makes it a truly enchanting
                    destination. Plan your trip to Bali and experience the magic of the Island of the Gods.
                </p>
            </div>
        </div>
    </div>
</main>

        <footer class="text-center mt-5">
            <p>&copy; 2023 Bali Tourism. All rights reserved.</p>
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