<?php
session_start();
require_once('../database/DbConnection.php');
$id = $_GET['packageID']??null;

// Fetch Package Data
$selectPackageData = "SELECT p.*, d.Destination, g.GuideName FROM packages p, destinations d, tour_guides g 
                        WHERE p.DestinationID=d.DestinationID
                        AND p.TourGuideID=g.TourGuideID
                        AND PackageID=?";
$packageSelectRes = $connection -> prepare($selectPackageData);
$packageSelectRes -> execute([$id]);
$packageData = $packageSelectRes -> fetch(PDO::FETCH_ASSOC);

$Title = $packageData['Title'] ?? null;
$Destination = $packageData['Destination'] ?? null;
$Description = $packageData['Description'] ?? null;
$Duration = $packageData['Duration'] ?? null;
$Languages = $packageData['Languages'] ?? null;
$Guide = $packageData['GuideName'] ?? null;
$Size = $packageData['Size'] ?? null;
$Price = $packageData['Price'] ?? null;
$HighlightOne = $packageData['Highlight1'] ?? null;
$HighlightTwo = $packageData['Highlight2'] ?? null;
$HighlightThree = $packageData['Highlight3'] ?? null;
$HighlightFour = $packageData['Highlight4'] ?? null;
$IncludedThings = $packageData['IncludedThings'] ?? null;
$ExcludedThings = $packageData['ExcludedThings'] ?? null;
$Information = $packageData['Info'] ?? null;
$ImageOne = $packageData['Image1'] ?? null;
$ImageTwo = $packageData['Image2'] ?? null;
$ImageThree = $packageData['Image3'] ?? null;
$Map = $packageData['Map'] ?? null;


$title = $Title;
ob_start();

// Fetch Itinerary Data 
$selectItinerary = "SELECT * FROM itineraries WHERE PackageID=?";
$itinerarySelectRes = $connection -> prepare($selectItinerary);
$itinerarySelectRes -> execute([$id]);
$itineraries = $itinerarySelectRes -> fetchAll(PDO::FETCH_ASSOC);

// Fetch Availability Data
$selectAvailability = "SELECT a.*, COALESCE(SUM(b.TotalTraveller), 0) AS TotalTraveller
                    FROM availability a
                    LEFT JOIN bookings b ON a.AvailabilityID = b.AvailabilityID AND b.BookingStatus IN ('pending', 'confirmed')
                    WHERE a.PackageID=?
                    GROUP BY a.AvailabilityID
                    ORDER BY a.StartDate ASC
";
$availabilitySelectRes = $connection -> prepare($selectAvailability);
$availabilitySelectRes -> execute([$id]);
$allAvailability = $availabilitySelectRes -> fetchAll(PDO::FETCH_ASSOC);

// Fetch Review Data
$selectReview = "SELECT r.*, u.FullName, u.ProfileImage FROM reviews r, users u 
                WHERE r.UserID = u.UserID
                AND r.PackageID=?
                ORDER BY CreatedAt DESC";
$reviewSelectRes = $connection -> prepare($selectReview);
$reviewSelectRes -> execute([$id]);
$reviews = $reviewSelectRes -> fetchAll(PDO::FETCH_ASSOC);

$ratingTotal=0;
foreach($reviews as $review){
    $ratingTotal = $ratingTotal + $review['Rating'];
}

$totalReviews = count($reviews);
if ($totalReviews > 0) {
    $overallRating = number_format($ratingTotal / $totalReviews, 1);
} else {
    // Handle the case where there are no reviews
    $overallRating = 0;
}
?>

<div class="container-fluid px-0">
    <!-- Header Section Start -->
    <div class="detail-head mx-5 mt-5">
        <h2 class="fw-bold"><?php echo $Title ?></h2>
        <p class="text-secondary d-flex align-items-center">Destination: <span class="fw-bold ms-2"><?php echo $Destination ?></span><i class="fa-solid fa-circle dot mx-2"></i><i class="fas fa-star text-warning me-1"></i><?php echo $overallRating ?> (<?php echo $totalReviews ?> <?php echo $totalReviews ==1 ? 'review':'reviews' ?>)</p>

        <div class="row mx-0">
            <div class="col-lg-8 d-none d-md-block">
                <div class="left-detail-img-container">
                    <img src="./../images/<?php echo $ImageOne ?>" alt="" class="w-100">
                </div>
            </div>
            <div class="col d-sm-block d-md-none d-lg-block">
                <div class="row">
                    <div class="right-detail-img-container">
                        <img src="./../images/<?php echo $ImageTwo ?>" alt="" class="w-100">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="right-detail-img-container">
                        <img src="./../images/<?php echo $ImageThree ?>" alt="" class="w-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Section End -->
    <!-- Body Section Start -->
    <div class="detail-body mx-5 row">
        <div class="col-lg-7 col-12 order-2 order-md-2 order-lg-1">
            <!-- Description Start -->
            <div class="row mt-4">
                <h4 class="fw-bold">Description</h4>
                <p class="mt-2"><?php echo $Description ?></p>
            </div>
            <!-- Description End -->
            <hr>
            <!-- About Start -->
            <div class="row mt-4">
                <h4 class="fw-bold">About this tour</h4>

                <div class="row mt-4">
                    <div class="col-1 ms-2">
                        <i class="fa-regular fa-clock text-dark"></i>
                    </div>
                    <div class="col">
                        <p class="text-dark">Duration <?php echo $Duration ?></p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-1 ms-2">
                        <i class="fa-solid fa-people-group text-dark"></i>
                    </div>
                    <div class="col">
                        <p class="text-dark">Tour Size <?php echo $Size ?></p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-1 ms-2">
                        <i class="fa-solid fa-globe text-dark"></i>
                    </div>
                    <div class="col">
                        <p class="text-dark">Guided in <?php echo $Languages ?></p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-1 ms-2">
                        <i class="fa-regular fa-flag text-dark"></i>
                    </div>
                    <div class="col">
                        <p class="text-dark">Guided by <?php echo $Guide ?></p>
                    </div>
                </div>
                
            </div>
            <!-- About End -->
            <hr>
            <!-- Highlights Start -->
            <div class="row mt-4">
                <h4 class="fw-bold">Hightlights</h4>

                <div class="row mt-4">
                    <div class="col-1 ms-2">
                    <i class="fa-solid fa-check text-dark"></i>
                    </div>
                    <div class="col">
                        <p class=""><?php echo $HighlightOne ?></p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-1 ms-2">
                    <i class="fa-solid fa-check text-dark"></i>
                    </div>
                    <div class="col">
                        <p class=""><?php echo $HighlightTwo ?></p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-1 ms-2">
                    <i class="fa-solid fa-check text-dark"></i>
                    </div>
                    <div class="col">
                        <p class=""><?php echo $HighlightThree ?></p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-1 ms-2">
                    <i class="fa-solid fa-check text-dark"></i>
                    </div>
                    <div class="col">
                        <p class=""><?php echo $HighlightFour ?></p>
                    </div>
                </div>
            </div>
            <!-- Highlights End -->
            <hr>
            <!-- Itinerary Start -->
            <div class="row mt-4">
                <h4 class="fw-bold mb-4">Itinerary</h4>

                <?php
                $lastIndex = count($itineraries) - 1; // Get the last index
                foreach($itineraries as $index => $itinerary){
                    $day = $itinerary['Day'];
                    $itineraryName = $itinerary['Name'];
                    $activities = $itinerary['Activity'];

                    $activityList = preg_split('/\s*-\s*/', trim($activities));// split the string at every '-'

                    echo "
                    <div class='mt-2 row'>
                        <div class='d-flex'>
                        <p class='fw-bold me-2'>Day $day</p> 
                        <span>$itineraryName</span>
                        </div>
                        <ul class='ms-4'>
                    ";
                    foreach ($activityList as $item) {
                        $trimmedItem = trim($item);
                        if ($trimmedItem !== '') {
                            echo "<li>$trimmedItem</li>";
                        }
                    }
                    echo "</ul>";
                    if ($index !== $lastIndex) {
                        echo "<hr class='itinerary-underline'>";
                    }
                    echo "</div>";
                }
                ?>
                <div class="mt-4 map">
                    <iframe src="<?php  echo $Map ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                
            </div>
            <!-- Itinerary End -->
            <hr>
            <!-- Includes Start -->
            <div class="row mt-4">
                <h4 class="fw-bold mb-4">What's included?</h4>
                <?php 
                $includesList = preg_split('/\s*-\s*/', trim($IncludedThings));
                echo "<ul class='ms-4'>";
                foreach ($includesList as $item) {
                    $trimmedItem = trim($item);
                    if ($trimmedItem !== '') {
                        echo "<li>$trimmedItem</li>";
                    }
                }
                echo "</ul>";
                ?>
            </div>
            <!-- Includes End -->
            <hr>
            <!-- Excludes Start -->
            <div class="row mt-4">
                <h4 class="fw-bold mb-4">What's not included?</h4>
                <?php 
                $excludesList = preg_split('/\s*-\s*/', trim($ExcludedThings));
                echo "<ul class='ms-4'>";
                foreach ($excludesList as $item) {
                    $trimmedItem = trim($item);
                    if ($trimmedItem !== '') {
                        echo "<li>$trimmedItem</li>";
                    }
                }
                echo "</ul>";
                ?>
            </div>
            <!-- Excludes End -->
            <hr>
            <!-- Information Start -->
            <div class="row mt-4">
                <h4 class="fw-bold mb-4">Important Information</h4>
                <?php 
                $informationList = preg_split('/\s*-\s*/', trim($Information));
                echo "<ul class='ms-4'>";
                foreach ($informationList as $item) {
                    $trimmedItem = trim($item);
                    if ($trimmedItem !== '') {
                        echo "<li>$trimmedItem</li>";
                    }
                }
                echo "</ul>";
                ?>
            </div>
            <!-- Information End -->
            <hr>
            <!-- Availability Start -->
            <div class="row mt-4" id="availability">
                <h4 class="fw-bold mb-4">Availability</h4>
                <div class="row mb-4" id="availability-list">
                    <?php
                    foreach ($allAvailability as $availability) {
                        $availabilityID = $availability['AvailabilityID'];
                        $startDate = $availability['StartDate'];
                        $endDate = $availability['EndDate'];
                        $availabilityPrice = $availability['Price'];
                        $totalTraveller = $availability['TotalTraveller'];

                        $availableSpace = $Size - $totalTraveller;
                
                        $formattedStartDate = date("Y-M-d", strtotime($startDate));
                        $formattedEndDate = date("Y-M-d", strtotime($endDate));
                
                        $currentDate = time();
                
                        echo "
                            <div class='row border shadow-sm p-3 mt-4 ms-2 availability-card";

                            if(strtotime($startDate)<$currentDate || $availableSpace==0){
                                echo " d-none";
                            }
                            
                        echo "' data-availability-id='$availabilityID'>
                                <div class='row'>
                                    <div class='col gx-0'>
                                        <div class='row'>
                                            <div class='col'>
                                                <p>From</p>
                                                <h5>$formattedStartDate</h5>
                                            </div>
                                            <div class='col'>
                                                <p>To</p>
                                                <h5>$formattedEndDate</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col gx-0'>
                                        <div class='row'>
                                            <div class='col-8 ps-5'>
                                                <p>Price:</p>
                                            </div>
                                            <div class='col'>
                                                <h5>฿$availabilityPrice</h5>
                                                <span class='text-secondary'>per person</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col'>
                                    <p class='";
                                    if($availableSpace == 1){
                                        echo "d-none";
                                    }
                                echo "'><i class='fa-solid fa-people-group text-dark'></i><span class='ms-1'>$availableSpace spaces left</span></p>";

                                    if($availableSpace == 1){
                                        echo "<p><i class='fa-solid fa-people-group text-dark'></i><span class='ms-1 text-danger'>Only $availableSpace space left!</span></p>";
                                    }
                                echo "</div>
                                    <div class='col d-flex justify-content-end btn-confirm-date'>
                                        <a href='./Booking.php?availabilityID=$availabilityID' class='btn btn-primary rounded-pill' target='_blank'>Confirm</a>
                                    </div>
                                </div>
                            </div>
                        ";
                    }
                    ?>
                    
                </div>
                <!-- Pagination -->
                <ul class="pagination2 pagination justify-content-start">
                    
                </ul>
            </div>
            <!-- Availability End -->
            <hr>
            <!-- Rating Start -->
            <div class="row mt-4">
                <h4 class="fw-bold mb-3">Rate Our Tour!</h4>
            
                <?php
                    $userID = $_SESSION['id'] ?? null;//current user ID
            
                    if(isset($_POST['btnReview'])){
                        $packageID = $_POST['packageID'];
                        $user = $_POST['userID'];
                        $rating = $_POST['ratings'] ?? null;
                        $comment = $_POST['comment'] ?? null;

                        $rating = $rating == "" ? 0:$rating;

                        if ($comment == null || empty($comment)) {
                            header("Location: PackageDetail.php?packageID=$packageID");
                            exit();
                        }
                        
                        $addReview = "INSERT INTO reviews(UserID,PackageID,Rating,Comment) VALUE(?,?,?,?)";
                        $reviewAddRes = $connection -> prepare($addReview);
                        $reviewAddRes -> execute([$user, $packageID, $rating, $comment]);

                        echo "<script>
                                    Swal.fire({
                                        title: 'Submitted!',
                                        text: 'Thanks for your review.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = './PackageDetail.php?packageID=" . $packageID . "';
                                        }
                                    });
                                </script>";
                    }
                ?>
                <form action="PackageDetail.php?packageID=<?php echo $id ?>" method="POST">
                    <div class="rating">
                        <input type="radio" name="ratings" value="5" id="star5" required/>
                        <label for="star5"></label>

                        <input type="radio" name="ratings" value="4" id="star4">
                        <label for="star4"></label>

                        <input type="radio" name="ratings" value="3" id="star3">
                        <label for="star3"></label>

                        <input type="radio" name="ratings" value="2" id="star2">
                        <label for="star2"></label>

                        <input type="radio" name="ratings" value="1" id="star1">
                        <label for="star1"></label>
                    </div>

                    <textarea name="comment" class="form-control bg-light mt-2" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Describe your experience"></textarea>

                    <input type="hidden" name="packageID" value="<?php echo $id ?>">
                    <input type="hidden" name="userID" value="<?php echo $userID ?>">

                    <button type="submit" name="btnReview" class="btn btn-primary mt-4">Submit</button>
                </form>

            </div>
            <!-- Rating End -->
            <hr>
            <!-- Rating Start -->
            <div class="row mt-4">
                <h4 class="fw-bold mb-3">Travelers' Reviews</h4>
                <?php
                    

                    $last = count($reviews) - 1; // Get the last index
                    foreach($reviews as $reviewIndex => $review){
                        $userName = $review['FullName'];
                        $profile = $review['ProfileImage'];
                        $ratings = $review['Rating'];
                        $comments = $review['Comment'];
                        $reviewTime = $review['CreatedAt'];

                        $formattedTime = date("Y-M-d", strtotime($reviewTime));

                        echo "
                            <div class='mt-4 review'>
                                <div class='row'>
                                <div class='col-1'>
                                <img src='
                        ";
                        echo $profile ? "./../images/" . $profile : "./../images/user_profile.jpg";
                        echo "' class='rounded-circle' width='50' height='50' alt='Profile Picture'>
                                </div>
                                <div class='col ms-4'>
                                <p class='mb-0'>$userName</p>
                                <span class='text-secondary'>$formattedTime</span>
                                </div>
                                </div>

                                <div class='row mt-3'>
                                <div class='col'>";
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $ratings 
                                        ? '<i class="fas fa-star text-warning me-1"></i>' 
                                        : '<i class="far fa-star text-warning me-1"></i>';
                                }       
                        echo "<p>$comments</p>
                                </div>
                                </div>";
                                if ($reviewIndex !== $last) {
                                    echo "<hr class='itinerary-underline'>";
                                }
                        echo "</div>";
                    }
                ?>
            </div>

        </div>
        <div class="col order-1 order-md-1 order-lg-2">
            <div class="mt-4 border shadow-sm availability-box rounded">
                <div class="row p-3">
                    <div class="col-3">
                        <h5>฿<?php echo $Price ?></h5>
                        <span class="text-secondary">per person</span>
                    </div>
                    <div class="col">
                        <a href="#availability" class="btn btn-lg btn-primary rounded-pill">Check Availability</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>