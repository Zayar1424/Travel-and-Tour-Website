<?php
$title = "WanderWay - Book Tours & Travel";
ob_start();
require_once('../database/DbConnection.php');
?>

<div class="container-fluid px-0">
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="pt-5 ps-3 ps-lg-5">
            <h1>Travel memories <br>you'll never forget </h1>
        </div>
        <div class="pt-3 ps-3 ps-lg-5">
            <h2>Choose from plenty of organized tours!</h2>
        </div>
        <div class="d-flex align-items-center justify-content-center">
            <div class="bg-light p-2 rounded-pill search-bar">
                <form class="d-flex" action="SearchResult.php" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="fa-solid fa-location-dot text-secondary"></i>
                        </span>
                        <input class="form-control border-0 shadow-none" name="searchKey" type="search" placeholder="Find your destination" aria-label="Search" style="background-color: transparent;">
                        
                    </div>
                    
                    <button class="btn btn-primary rounded-pill" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Page Header End    -->

    <!-- Destinations Start -->
    <div class="px-3 px-lg-5 pt-5">
        <h4 class="fw-bold">Our Destinations</h4>
    </div>
    <div class="slider-container px-2 px-lg-5 pt-3">
        <button id="prevBtn" class="nav-btn bg-secondary fs-6">&#10094;</button>
        <div class="slider">
            <div class="slider-track">
                <?php

                $selectDestination = "SELECT * FROM destinations";
                $destinationSelectRes = $connection->prepare($selectDestination);
                $destinationSelectRes->execute();
                $destinations = $destinationSelectRes->fetchAll(PDO::FETCH_ASSOC);

                foreach ($destinations as $destination) {
                    $destinationName = $destination['Destination'];
                    $destinationImage = $destination['Image'];
                ?>
                    <div class="slide">
                        <div class="card-1">
                            <div class="image-container">
                                <a href="./SearchResult.php?searchKey=<?php echo $destinationName ?>"><img src="./../images/<?php echo $destinationImage ?>" alt="<?php echo $destinationName ?>"></a>
                            </div>
                            
                            <h5 class="card-title"><?php echo $destinationName ?></h5>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <button id="nextBtn" class="nav-btn bg-secondary fs-6">&#10095;</button>
    </div>
    <!-- Destinations End -->

    <!-- Latest Adventures Start -->
    <div>
        <div class="px-3 px-lg-5 pt-5 row gx-0">
            <div class="col">
                <h4 class="fw-bold">Latest Adventures</h4>
            </div>
            <div class="col d-flex justify-content-end h-50">
                <a href="./Package.php" class="btn btn-sm btn-primary pt-2">See More</a>
            </div>
        </div>
        <div class="px-2 px-lg-5 pt-4">
            <div class="row justify-content-center gx-0">
            <?php
                    $selectPackages = "SELECT * FROM packages WHERE Active=? ORDER BY CreatedAt DESC LIMIT 4";
                    $packageSelectRes = $connection->prepare($selectPackages);
                    $packageSelectRes -> execute([0]);
                    $packages = $packageSelectRes->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($packages as $package) {
                        $packageID = $package['PackageID'];
                        echo '
                            <div class="col-12 col-md-6 col-lg-3 mt-3 d-flex justify-content-center package-card">
                                <a href="./PackageDetail.php?packageID='.$packageID.'">
                                <div class="card">
                                    <div class="image-container"><img src="./../images/'.$package['Image1'] .'" class=" w-100"></div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">'.$package['Title'].'</h5>
                                        <div class="row mt-2">
                                            <div class="col-1"><i class="fa-regular fa-clock"></i></div>
                                            <div class="col ms-2"><p class="card-text text-secondary">'.$package['Duration'].'</p></div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-1"><i class="fa-solid fa-people-group"></i></div>
                                            <div class="col ms-2"><p class="card-text text-secondary">'.$package['Size'].' people tour</p></div>
                                        </div>
                                        
                                        
                                        <div class="mt-auto">
                                            <p class="card-text">฿'.$package['Price'].' per person</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        ';
                    }
                ?>
            </div>
        </div>
    </div>
    <!-- Latest Adventures End -->

    <div class="d-flex justify-content-center align-items-center my-5 flex-column p-2">
        <h2 class="mt-4">To plan a few days trip?</h2>
        <div>
            <p class="mt-4 text-center">We offer one of the best services and handle all the arrangements for you.</p>
        </div>       
        <a href="./Contact.php" class="btn btn-lg btn-primary my-3"><i class="fa-regular fa-message me-3"></i>Tell us what you need</a>
    </div>


    
</div>

<!-- <script>
                                    // Marquee placeholder effect for small screens
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const input = document.querySelector('.search-bar input[name="searchKey"]');
                                        if (!input) return;
                                        const original = 'Find your destination';
                                        let marquee = original + '   ';
                                        let pos = 0;
                                        let interval = null;

                                        function startMarquee() {
                                            if (window.innerWidth <= 768) {
                                                if (interval) clearInterval(interval);
                                                interval = setInterval(() => {
                                                    input.setAttribute('placeholder', marquee.substring(pos) + marquee.substring(0, pos));
                                                    pos = (pos + 1) % marquee.length;
                                                }, 150);
                                            } else {
                                                if (interval) clearInterval(interval);
                                                input.setAttribute('placeholder', original);
                                            }
                                        }

                                        startMarquee();
                                        window.addEventListener('resize', startMarquee);
                                    });
                                    </script> -->

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>