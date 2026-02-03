<?php
$title = "Bookings";
ob_start();
session_start();
require_once('../database/DbConnection.php');

$id = $_SESSION['id'] ?? null;
$name = $_SESSION['fullname'] ?? null;
$email = $_SESSION['email'] ?? null;
$phone = $_SESSION['Phone'] ?? null;
$role = $_SESSION['role'] ?? null;
$profile = $_SESSION['profile'] ?? null;

    $noResult = false;

    $selectPackages = "SELECT p.PackageID,p.Title,p.Image1,a.*, b.BookingID,b.BookingCode,b.TotalTraveller,b.BookingStatus,pm.TotalPrice FROM packages p, availability a, bookings b, payments pm
                        WHERE p.PackageID = a.PackageID
                        AND a.AvailabilityID = b.AvailabilityID
                        AND b.BookingID = pm.BookingID
                        AND b.UserID=?";
    $packageSelectRes = $connection->prepare($selectPackages);
    $packageSelectRes->execute([$id]);
    $packages = $packageSelectRes->fetchAll(PDO::FETCH_ASSOC);
    $packageCount = count($packages);

    if($packageCount==0){
        $noResult = true;
    }
?>

<div class="container-fluid px-0">
    <div class="mt-4 px-3 px-lg-5">
        <h4 class="fw-bold">Your Bookings</h4>
    </div>
        <?php
        if($noResult){
            echo '
            <p class="mt-4 mx-3 mx-lg-5">No booking found.</p>
            ';
        }
        ?>

    <div class="px-2 px-lg-5 mt-1">
        <div class="row my-booking-cards justify-content-center justify-content-lg-start gx-0">
            <?php
            foreach ($packages as $package) {
                $packageID = $package['PackageID'];
                $formattedStartDate = date("Y-M-d", strtotime($package['StartDate']));
                echo '
                    <div class="col-12 col-md-6 col-lg-3 mt-3 package-card d-flex justify-content-center">
                        <a href="./BookingDetail.php?bookingID='.$package['BookingID'].'">
                        <div class="card">
                            <div class="image-container"><img src="./../images/'.$package['Image1'] .'" class=" w-100"></div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">'.$package['Title'].'</h5>
                                <p class="card-text mt-2">Booking Code: '.$package['BookingCode'].'</p>
                                <p class="card-text">Total Price: '.$package['TotalPrice'].'</p>
                                <p class="card-text text-secondary">Start Date: '.$formattedStartDate.'</p>
                                <p class="card-text text-secondary">Total Traveler: '.$package['TotalTraveller'].'</p>
                                <div class="mt-auto">';

                                if($package['BookingStatus'] == 'pending'){
                                    echo '<p class="card-text">Status: <span class="text-primary">Pending</span></p>';
                                }
                                if($package['BookingStatus'] == 'confirmed'){
                                    echo '<p class="card-text">Status: <span class="text-success">Confirmed</span></p>';
                                }
                                if($package['BookingStatus'] == 'cancelled'){
                                    echo '<p class="card-text">Status: <span class="text-danger">Cancelled</span></p>';
                                }
                                    
                echo '</div>
                            </div>
                        </div>
                        </a>
                    </div>
                ';
            }
            ?>
        </div>
        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-start mt-4 <?php echo $noResult ? 'd-none' : '' ?>"></ul>
        </nav>
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>