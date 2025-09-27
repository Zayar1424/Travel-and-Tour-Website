<?php
$title = "Packages - WanderWay";
ob_start();
require_once('../database/DbConnection.php');

$sort = isset($_GET['sort']) ? $_GET['sort'] : "latest";

    // Define sorting conditions
    $orderBy = "CreatedAt DESC";
    if ($sort == "price_asc") {
        $orderBy = "Price ASC";
    } elseif ($sort == "price_desc") {
        $orderBy = "Price DESC";
    }
?>

<div class="container-fluid px-0">
    <!-- Page Header Start -->
    <div class="page-header1 d-flex justify-content-center">
        <div class="pt-5">
            <h2>Our Packages</h2>
            <p class="text-center mt-4"><a href="./Home.php" class="text-decoration-none">Home</a>><span><u>Package</u></span></p>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Package Body Start -->
    <div class="mx-0 px-5 mt-5 row">
        <!-- Filter Start -->
        <div class="col-12 col-lg-3">
            <div class="row">
                <h4 class="fw-bold">Filters</h4>
            </div>
            <div class="row">
                <form id="filterForm" class="p-3 border rounded">

                <!-- Destination Dropdown -->
                <label for="destinationSelect" class="form-label">Destination</label>
                <select id="destinationSelect" class="form-select">
                    <option value="">All Destinations</option>
                    <?php
                    $selectDestination = "SELECT * FROM destinations";
                    $destinationSelectRes = $connection -> prepare($selectDestination);
                    $destinationSelectRes -> execute();
                    $destinations = $destinationSelectRes -> fetchAll(PDO::FETCH_ASSOC);

                    
                    foreach ($destinations as $item){
                        $destinationID = $item['DestinationID'];
                        $destinationName = $item['Destination'];
                        echo "<option value='$destinationID'>$destinationName</option>";
                    }
                    ?>
                </select>

                <!-- Duration Range -->
                <label for="durationRange" class="form-label mt-2">Max Duration (Days): <span id="durationValue">30</span></label>
                <input type="range" class="form-range" id="durationRange" min="1" max="30" step="1" value="30">

                <!-- Price Range -->
                <label for="priceRange" class="form-label mt-2">Max Price (฿): <span id="priceValue">50000</span></label>
                <input type="range" class="form-range" id="priceRange" min="1000" max="50000" step="1000" value="50000">

                <!-- Size Range -->
                <label for="sizeRange" class="form-label mt-2">Max Group Size: <span id="sizeValue">30</span></label>
                <input type="range" class="form-range" id="sizeRange" min="2" max="25" step="1" value="30">

                <!-- Apply Filter Button -->
                <button type="button" id="applyFilters" class="btn btn-primary mt-3">Apply Filters</button>
                </form>

            </div>
        </div>
        <!-- Filter End -->
        <!-- Packages Start -->
        <div class="col">
        <div class="col d-flex justify-content-end">
            <div class="row">
                <div class="col">
                    <p class="mt-2">Sort by:</p>
                </div>
                <div class="col">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                            if(!empty($_GET['sort'])){
                                if($_GET['sort'] == "latest"){
                                    echo "Latest";
                                }
                                elseif($_GET['sort'] == "price_asc"){
                                    echo "Price - low to high";
                                }
                                elseif($_GET['sort'] == "price_desc"){
                                    echo "Price - high to low";
                                }
                            }else{
                                echo "Latest";
                            }
                        ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item sort-option" href="#" data-sort="latest">Latest</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="price_asc">Price - low to high</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="price_desc">Price - high to low</a></li>
                    </ul>
                </div>

                </div>
            </div>
            
        </div>
        <div class="row">
            <?php

                    $noResult = false;
                    // Get filter values from GET request
                    $destination = isset($_GET['destination']) && $_GET['destination'] !== '' ? $_GET['destination'] : null;
                    $duration = isset($_GET['duration']) ? $_GET['duration'] : null;
                    $price = isset($_GET['price']) ? $_GET['price'] : null;
                    $size = isset($_GET['size']) ? $_GET['size'] : null;
                    

                    // Base Query
                    $query = "SELECT * FROM packages WHERE Active = ?";

                    // Conditions Array
                    $conditions = [];
                    $params = [0];

                    if ($destination) {
                        $conditions[] = "DestinationID = ?";
                        $params[] = $destination;
                    }

                    // Convert Duration Format (Extract number from "X days")
                    if ($duration) {
                        $conditions[] = "CAST(SUBSTRING_INDEX(Duration, ' ', 1) AS UNSIGNED) <= ?";
                        $params[] = $duration;
                    }

                    if ($price) {
                        $conditions[] = "Price <= ?";
                        $params[] = $price;
                    }

                    if ($size) {
                        $conditions[] = "Size <= ?";
                        $params[] = $size;
                    }

                    // Append conditions
                    if (!empty($conditions)) {
                        $query .= " AND " . implode(" AND ", $conditions);
                    }

                    // Append sorting
                    $query .= " ORDER BY $orderBy";

                    // Execute Query
                    $packageSelectRes = $connection->prepare($query);
                    $packageSelectRes->execute($params);
                    $packages = $packageSelectRes->fetchAll(PDO::FETCH_ASSOC);
                    $packageCount = count($packages);

                    if($packageCount==0){
                        $noResult = true;
                    }

                    if($noResult){
                        echo '
                        <p class="mt-4 mx-5">No package found.</p>
                        ';
                    }

                    foreach ($packages as $package) {
                        $packageID = $package['PackageID'];
                        echo '
                            <div class="col-12 col-sm-6 col-lg-4 mt-3 package-card">
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
            <!-- Pagination -->
            <ul class="pagination justify-content-start mt-4">
                    
            </ul>
        </div>
        </div>
        <!-- Packages End -->
    </div>
    <!-- Packages Body End -->


</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>