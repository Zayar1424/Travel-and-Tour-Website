<?php
$title = "About Us - WanderWay";
ob_start();
require_once('../database/DbConnection.php');
?>

<div class="container-fluid px-0">
    <!-- Page Header Start -->
    <div class="page-header2 d-flex justify-content-center">
        <div class="pt-5">
            <h2>About Us</h2>
            <p class="text-center mt-4"><a href="./Home.php" class="text-decoration-none">Home</a>><span><u>About Us</u></span></p>
        </div>
    </div>
    <!-- Page Header End -->
    <!-- About Section Start -->
    <div class="mx-0 mx-lg-5 my-5">
        <h4 class="mx-4 mx-lg-5 fw-bold">The Journey Behind WanderWay</h4>
        <p class="mx-4 mx-lg-5 mt-4">
        At WanderWay Tours, we believe that travel is more than just sightseeing — it’s about discovery, 
        connection, and creating lasting memories. Founded in 2024 by passionate traveler Nathan Gil,
        WanderWay Tours emerged from a desire to make meaningful travel experiences more accessible to 
        everyone. Inspired by countless journeys and personal experiences, Nathan recognized a common 
        challenge faced by many travelers: the struggle to find trustworthy and personalized tour services. 
        That realization sparked the vision behind WanderWay Tours.
        </p>
        <p class="mx-4 mx-lg-5">
        Our mission is to provide well-curated tour packages that go beyond the typical 
        tourist experience. From cultural immersions and scenic landscapes to hidden gems and local 
        secrets, we aim to help travelers uncover the true essence of each destination. 
        Each tour is carefully crafted by a team of travel enthusiasts who share a deep love for 
        exploration and storytelling.
        </p>

        <h4 class="mx-4 mx-lg-5 mt-4 fw-bold">What We Offer</h4>
        <p class="mx-4 mx-lg-5 mt-4">
        Headquartered in the heart of Bangkok, Thailand, WanderWay Tours currently focuses on offering 
        domestic travel experiences across the country’s most iconic and lesser-known locations. 
        Whether it’s the tranquil temples of Chiang Rai, the vibrant floating markets of Bangkok, 
        or the white sand beaches of Phuket, we strive to bring each destination to life through guided 
        experiences that are informative, enjoyable, and hassle-free.
        </p>
        <p class="mx-4 mx-lg-5">
        At WanderWay Tours, customer satisfaction is not just a goal — it’s a promise. We listen to 
        feedback, continually improve, and treat every traveler as part of our growing family. We want 
        your journey with us to be as smooth and enjoyable as the destinations themselves.
        </p>
        <p class="mx-4 mx-lg-5">
        Whether you’re a solo traveler, a couple seeking adventure, or a family looking for a memorable 
        holiday, WanderWay Tours is here to guide your way. Join us in exploring the richness of 
        Thailand and beyond — one journey at a time.
        </p>
    </div>
    <!-- About Section End -->
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>