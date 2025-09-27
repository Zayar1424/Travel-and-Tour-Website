<?php
$title = "Bookings";
ob_start();
require_once('../database/DbConnection.php');

// confirm and cancel booking
if(isset($_POST['btnConfirm'])){
    $ID = $_POST['bookingID'];
    $confirmPaymentStatus = "UPDATE payments SET PaymentStatus=? WHERE BookingID=?";
    $confirmPaymentRes = $connection -> prepare($confirmPaymentStatus);
    $confirmPaymentRes -> execute(['confirmed',$ID]);

    $confirmBookingStatus = "UPDATE bookings SET BookingStatus=? WHERE BookingID=?";
    $confirmBookingRes = $connection -> prepare($confirmBookingStatus);
    $confirmBookingRes -> execute(['confirmed',$ID]);

    $_SESSION['bookingID'] = $ID;

    echo "<script>
                Swal.fire({
                  title: 'Done!',
                    text: 'Booking confirmed.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                if (result.isConfirmed) {
                        window.location.href = './BookingDetail.php?bookingID=$ID';
                    }
                });
            </script>";  
}

if(isset($_POST['btnCancel'])){
    $ID = $_POST['bookingID'];
    $cancelPaymentStatus = "UPDATE payments SET PaymentStatus=? WHERE BookingID=?";
    $cancelPaymentRes = $connection -> prepare($cancelPaymentStatus);
    $cancelPaymentRes -> execute(['cancelled',$ID]);

    $cancelBookingStatus = "UPDATE bookings SET BookingStatus=? WHERE BookingID=?";
    $cancelBookingRes = $connection -> prepare($cancelBookingStatus);
    $cancelBookingRes -> execute(['cancelled',$ID]);

    $_SESSION['bookingID'] = $ID;

    echo "<script>
                Swal.fire({
                    title: 'Done!',
                    text: 'Booking cancelled.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                if (result.isConfirmed) {
                        window.location.href = './BookingDetail.php?bookingID=$ID';
                    }
                });
            </script>";
}
$bookingID = $_GET['bookingID'] ?? $_SESSION['bookingID'] ?? null;

// fetch all booking data
$selectBooking = "SELECT b.*, a.StartDate, pm.TotalPrice, pm.Screenshot, pm.PaymentStatus, pt.TypeName, p.Title, p.Duration, p.Languages, p.Image1, d.Destination, g.GuideName 
                    FROM bookings b, availability a, payments pm, payment_types pt, packages p, destinations d, tour_guides g 
                    WHERE b.AvailabilityID = a.AvailabilityID
                    AND b.BookingID = pm.BookingID
                    AND pm.PaymentTypeID = pt.PaymentTypeID
                    AND a.PackageID = p.PackageID
                    AND p.DestinationID = d.DestinationID
                    AND p.TourGuideID = g.TourGuideID
                    AND b.BookingID=?";
$bookingSelectRes = $connection -> prepare($selectBooking);
$bookingSelectRes -> execute([$bookingID]);
$bookingData = $bookingSelectRes ->fetch(PDO::FETCH_ASSOC);

$startDate = $bookingData['StartDate'];
$formattedStartDate = date("Y-M-d", strtotime($startDate));
?>

<div class="container-fluid overflow-auto container-scroll">
    <div class="row align-items-start mb-4">
        <!-- Left Form Start -->
        <div class="col-6">
            <div class="card mt-5 shadow-sm input-form pb-2">
                <div class="card-body">
                    <div class="row mt-2">
                        <h5>Booking Details</h5>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Booking Code</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['BookingCode'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Full Name</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['FullName'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Email</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['Email'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Phone</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['Phone'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Date of Birth</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['DOB'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Gender</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['Gender'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Total Traveler</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['TotalTraveller'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Payment Type</p>
                        </div>
                        <div class="col">
                            <p>- <?php echo $bookingData['TypeName'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Total Price</p>
                        </div>
                        <div class="col">
                            <p>- ฿<?php echo $bookingData['TotalPrice'] ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Payment Status</p>
                        </div>
                        <div class="col">
                            <?php
                            if($bookingData['PaymentStatus'] == 'pending'){
                                echo "<p>- <span class='text-primary'>Pending</span></p>";
                            }
                            if($bookingData['PaymentStatus'] == 'confirmed'){
                                echo "<p>- <span class='text-success'>Confirmed</span></p>";
                            }
                            if($bookingData['PaymentStatus'] == 'cancelled'){
                                echo "<p>- <span class='text-danger'>Cancelled</span></p>";
                            }
                            ?>
                            
                        </div>
                    </div>
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <p class="fw-bold">Screenshot</p>
                        </div>
                        <div class="col">
                            <img src="./../images/<?php echo $bookingData['Screenshot'] ?>" alt="" class="w-100 img-thumbnail img-fluid">
                        </div>
                    </div>

                    <form action="BookingDetail.php" method="POST">
                    <div class="row mt-4 <?php echo $bookingData['PaymentStatus'] != 'pending' ? 'd-none' : '' ?>"> 
                            <input type="hidden" name="bookingID" value="<?php echo $bookingID ?>">     
                            <div class="col">
                            <button type="submit" name="btnCancel" class="btn btn-danger fs-6">Cancel</button>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <button type="submit" name="btnConfirm" class="btn btn-success fs-6">Confirm</button>
                            </div>   
                    </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- Left Form End -->
        <!-- Right Content Start -->
        <div class="col">
            <div class="mt-4">
                <div class="summary-box border pb-4">
                    <div class="row pt-4 px-4">
                        <div class="col-5 col-md-2">
                            <img src="./../images/<?php echo $bookingData['Image1'] ?>" alt="" class="w-100 rounded">
                        </div>
                        <div class="col">
                            <h5 class="fw-bold"><?php echo $bookingData['Title'] ?></h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row px-4">
                        <div class="row">
                            <div class="col-1 ms-2">
                            <i class="fa-solid fa-location-dot text-dark"></i>
                            </div>
                            <div class="col">
                                <p class="text-dark">Destination: <?php echo $bookingData['Destination'] ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1 ms-2">
                            <i class="fa-regular fa-calendar text-dark"></i>
                            </div>
                            <div class="col">
                                <p class="text-dark"><?php echo $formattedStartDate ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1 ms-2">
                                <i class="fa-regular fa-clock text-dark"></i>
                            </div>
                            <div class="col">
                                <p class="text-dark">Duration: <?php echo $bookingData['Duration'] ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1 ms-2">
                                <i class="fa-solid fa-globe text-dark"></i>
                            </div>
                            <div class="col">
                                <p class="text-dark">Guided in <?php echo $bookingData['Languages'] ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1 ms-2">
                                <i class="fa-regular fa-flag text-dark"></i>
                            </div>
                            <div class="col">
                                <p class="text-dark">Guided by <?php echo $bookingData['GuideName'] ?></p>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
        <!-- Right Content End -->
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>