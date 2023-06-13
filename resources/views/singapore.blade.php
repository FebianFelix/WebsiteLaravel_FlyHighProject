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
                <img class="gambars" src="foto\singapore.png" alt="Foto singapore">
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-container">
            <p>
                                    Singapore, also known as the "Lion City," is a vibrant and modern island city-state in Southeast Asia. It is renowned for its stunning architecture, diverse cultural heritage, and thriving urban attractions. Despite its small size, Singapore offers a unique blend of tradition and innovation, making it an enticing destination for travelers.
                                </p>
                                <p>
                                    One of the highlights of Singapore is its impressive skyline, adorned with iconic landmarks such as Marina Bay Sands and Gardens by the Bay. Visitors can enjoy panoramic views from observation decks, stroll along the waterfront promenade, or take a river cruise to experience the city's breathtaking architecture and cityscape.
                                </p>
                                <p>
                                    Singapore is also known for its multiculturalism and rich heritage. The city-state is home to various ethnic neighborhoods, including Chinatown, Little India, and Kampong Glam. Each area offers a distinct cultural experience, from vibrant street markets and historical temples to colorful festivals and traditional cuisine.
                                </p>
                                <p>
                                    Food enthusiasts will be delighted by Singapore's diverse culinary scene. The city-state is renowned as a gastronomic paradise, offering a fusion of flavors from different cultures. Visitors can savor local delicacies like Hainanese chicken rice, laksa, and chili crab, or indulge in international cuisines at Michelin-starred restaurants and hawker centers.
                                </p>
                                <p>
                                    Whether you're exploring futuristic architecture, immersing yourself in diverse cultures, indulging in mouthwatering cuisine, or enjoying nature and the arts, Singapore promises a captivating and enriching experience. Plan your trip to Singapore and discover the dynamic city-state that seamlessly blends tradition and innovation.
                                </p>
            </div>
        </div>
    </div>
</main>

        <footer class="text-center mt-5">
            <p>&copy; 2023 Singapore Tourism. All rights reserved.</p>
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