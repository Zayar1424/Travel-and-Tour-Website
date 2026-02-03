<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../database/DbConnection.php');

$id = $_SESSION['id'] ?? null;
$name = $_SESSION['fullname'] ?? null;
$email = $_SESSION['email'] ?? null;
$phone = $_SESSION['phone'] ?? null;
$role = $_SESSION['role'] ?? null;
$profile = $_SESSION['profile'] ?? null;

if (!isset($id)) 
{
    echo "<script>window.alert('Something went wrong! Please login again..')</script>";
	echo "<script>window.location='./../auth/Login.php'</script>";  
}

$availabilityID = $_GET['availabilityID'] ?? $_SESSION['availabilityID'] ?? null;

// Fetch Availability Data
$selectAvailability = "SELECT * FROM availability WHERE AvailabilityID=?";
$availabilitySelectRes = $connection -> prepare($selectAvailability);
$availabilitySelectRes -> execute([$availabilityID]);
$availabilityData = $availabilitySelectRes -> fetch(PDO::FETCH_ASSOC);

$packageID = $availabilityData['PackageID']??null;
$startDate = $availabilityData['StartDate']??null;
$formattedStartDate = date("d-M-Y", strtotime($startDate)); // format start date
$endDate = $availabilityData['EndDate']??null;
$availabilityPrice = $availabilityData['Price']??null;
$getID = $availabilityData['AvailabilityID']??null;

// Fetch Package Data
$selectPackageData = "SELECT p.Title, p.Duration, p.Languages, p.Size, p.Image1, d.Destination, g.GuideName FROM packages p, destinations d, tour_guides g 
                        WHERE p.DestinationID=d.DestinationID
                        AND p.TourGuideID=g.TourGuideID
                        AND PackageID=?";
$packageSelectRes = $connection -> prepare($selectPackageData);
$packageSelectRes -> execute([$packageID]);
$packageData = $packageSelectRes -> fetch(PDO::FETCH_ASSOC);

$title = $packageData['Title'] ?? null;
$destination = $packageData['Destination'] ?? null;
$duration = $packageData['Duration'] ?? null;
$languages = $packageData['Languages'] ?? null;
$guide = $packageData['GuideName'] ?? null;
$size = $packageData['Size'] ?? null;
$packageImage = $packageData['Image1'] ?? null;

// Fetch & Caculate Total Spaces left
$selectTotalTraveller = "SELECT  COALESCE(SUM(b.TotalTraveller), 0) AS TotalTraveller
                    FROM availability a
                    LEFT JOIN bookings b ON a.AvailabilityID = b.AvailabilityID
                    WHERE a.PackageID=?
                    AND a.AvailabilityID=?
                    AND b.BookingStatus IN ('pending', 'confirmed')
                    ORDER BY a.StartDate ASC
";
$totalTravellerSelectRes = $connection -> prepare($selectTotalTraveller);
$totalTravellerSelectRes -> execute([$packageID, $availabilityID]);
$totalTraveller = $totalTravellerSelectRes -> fetch(PDO::FETCH_ASSOC);

$availableTraveller = $size - $totalTraveller['TotalTraveller'];

// Fetch Payment Types
$selectPaymentType = "SELECT * FROM payment_types";
$paymentTypeSelectRes = $connection -> prepare($selectPaymentType);
$paymentTypeSelectRes -> execute();
$paymentTypes = $paymentTypeSelectRes -> fetchAll(PDO::FETCH_ASSOC);

// Book
if(isset($_POST['btnBook'])){

    // Validation
    $validation = [
        'fullNameStatus' => false,
        'emailNameStatus' => false,
        'phoneStatus' => false,
        'dobStatus' => false,
        'dobCheck' => false,
        'genderStatus' => false,
        'totalTravellerStatus' => false,
        'paymentTypeStatus' => false,
        'imageStatus' => false,
        'travelerStatus' => false,
    ];

    $validation['fullNameStatus'] = $_POST['fullName'] == "" ? true:false;
    $validation['emailNameStatus'] = $_POST['email'] == "" ? true:false;

    // $phonePattern = "/^[0-9]{10}$/";
    $validation['phoneStatus'] = $_POST['phone'] == "" ? true:false;

    // $validation['dobStatus'] = $_POST['dobDay'] ?? null == "" || $_POST['dobMonth'] ?? null == "" || $_POST['dobYear'] ?? null == "" ? true:false;

    $dobDay = $_POST['dobDay'] ?? null;
    $dobMonth = $_POST['dobMonth'] ?? null;
    $dobYear = $_POST['dobYear'] ?? null;
    if($dobDay == "" || $dobMonth == "" || $dobYear == ""){
        $validation['dobStatus'] = true;
    }else{
        $validation['dobStatus'] = false;
    }
    $validation['dobCheck'] = !checkdate($dobMonth, $dobDay, $dobYear) ? true:false;

    $genderValue = $_POST['gender'] ?? null;
    $validation['genderStatus'] = $genderValue == "" ? true:false;

    $paymentTypeValue = $_POST['paymentType'] ?? null;
    $validation['paymentTypeStatus'] = $paymentTypeValue == "" ? true:false;

    $validation['imageStatus'] = $_FILES['image']['name'] == "" ? true:false;

    $validation['travelerStatus'] = $_POST['totalPeople'] > $availableTraveller ? true:false;
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - <?php echo $title ?></title>

    <!-- bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- css link -->
    <link rel="stylesheet" href="./style.css">

    <!-- fontawesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- logo font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- sweet alert link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jquery link -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

</head>

<body class="booking-body mb-5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-0 px-lg-5">
        <div class="container-fluid mx-3 mx-md-5">
            <a class="navbar-brand" href="./Home.php"><p class="logo">WanderWay</p></a>   
        </div>
    </nav>

    <div class="mx-3 mx-md-5 gx-0">
        <div class="mx-0 mx-lg-5 gx-0">

            <div class="mx-0 mx-lg-3 container-fluid gx-0">
                <div class="mt-4">
                    <h3 class="fw-bold"><?php echo $title ?></h2>
                </div>
                <form action="Booking.php?availabilityID=<?php echo $getID ?>" method="POST" enctype="multipart/form-data">
                <div class="row gx-0">
                    
                    <!-- Left Content Start -->
                    <div class="col-12 col-md-6">
                        <div class="personal-detail-box mb-5">
                            <h5 class="mt-4 fw-bold">Personal Details</h5>
                            <div class="mt-2 p-2">

                                <input type="hidden" name="userID" value="<?php echo $id ?>">
                                <input type="hidden" name="availabilityID" value="<?php echo $availabilityID ?>">
                                <input type="hidden" name="bookingCode" id="bookingCode" value="">
                                <div class="row mt-2">
                                    <h6>Full Name</h6>
                                    <input type="text" id="" name="fullName" class="form-control underline-input" placeholder="Enter your full name" value="<?php echo $_POST['fullName'] ?? '' ?>">
                                    <?php
                                    if(isset($_POST['btnBook'])){
                                        if($validation['fullNameStatus']){
                                            echo '
                                            <div class="row">                                     
                                                <small class="text-danger ms-2">Full name is required!</small>
                                            </div>
                                            ';
                                        }
                                    }
                                    ?>
                                </div>
                                    
                                <div class="row mt-4">
                                    <h6>Email</h6>
                                    <input type="email" id="" name="email" class="form-control underline-input" placeholder="Enter your email" value="<?php echo $email ?? $_POST['email'] ?? '' ?>">
                                    <?php
                                    if(isset($_POST['btnBook'])){
                                        if($validation['emailNameStatus']){
                                            echo '
                                            <div class="row">                                     
                                                <small class="text-danger ms-2">Email is required!</small>
                                            </div>
                                            ';
                                        }
                                    }
                                    ?>
                                </div>
                                
                                <div class="row mt-4">
                                    <h6>Phone</h6>
                                    <input type="text" id="" name="phone" class="form-control underline-input" placeholder="Enter your phone number" value="<?php echo $phone ?? $_POST['phone'] ?? '' ?>">
                                    <?php
                                    if(isset($_POST['btnBook'])){
                                        if($validation['phoneStatus']){
                                            echo '
                                            <div class="row">                                     
                                                <small class="text-danger ms-2">Phone number is required!</small>
                                            </div>
                                            ';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="dob mt-4">
                                    <div class="form-group">
                                        <label for="dob"><h6>Date of Birth</h6></label>
                                        <div class="row mt-2">
                                        <div class="col-4">
                                        <select class="form-control custom-select" id="day" aria-label="Select Day" name="dobDay">
                                            <option value="" disabled selected>Day</option>
                                             
                                        </select>
                                        </div>
                                        
                                        <div class="col-4">
                                        <select class="form-control custom-select" id="month" aria-label="Select Month" name="dobMonth">
                                            <option value="" disabled selected>Month</option>
                                             
                                        </select>
                                        </div>

                                        <div class="col-4">
                                        <select class="form-control custom-select" id="year" aria-label="Select Year" name="dobYear">
                                            <option value="" disabled selected>Year</option>
                                             
                                        </select>
                                        </div>
                                        
                                        
                                        </div>
                                        <?php
                                        if(isset($_POST['btnBook'])){

                                            if($validation['dobStatus']){
                                                echo '
                                                <div class="row">                                     
                                                    <small class="text-danger ms-2">Date of birth is required!</small>
                                                </div>
                                                ';
                                            }

                                            else if ($validation['dobCheck']){
                                                echo '
                                                <div class="row">                                     
                                                    <small class="text-danger ms-2">Enter a valid date!</small>
                                                </div>
                                                ';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>


                                <div class="mt-4">
                                    <div class="form-group" >
                                    <label class="mb-2"><h6>Gender</h6></label><br>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="Male" <?php if(isset($_POST['gender'])) { if($_POST['gender'] == 'Male') echo 'checked'; } ?>>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="Female" <?php if(isset($_POST['gender'])) { if($_POST['gender'] == 'Female') echo 'checked'; } ?>>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>

                                    </div>
                                    <?php
                                    if(isset($_POST['btnBook'])){
                                        if($validation['genderStatus']){
                                            echo '
                                            <div class="row">                                     
                                                <small class="text-danger ms-2">Gender is required!</small>
                                            </div>
                                            ';
                                        }
                                    }
                                    ?>

                                </div>



                            </div>
                            
                            
                        </div>
                        <hr>
                        <div class="payment-detail-box mt-5">
                            <h5 class="fw-bold">Payment Details</h5>
                            <div class="mt-2">

                                <div class="row mt-2 gx-0">
                                    <h6 class="mb-3">Choose Payment</h6>

                                    <?php foreach ($paymentTypes as $index => $paymentType): 
                                        $paymentTypeID = $paymentType['PaymentTypeID'];
                                        $typeName = $paymentType['TypeName'];
                                        $accountName = $paymentType['AccountName'];
                                        $accountNumber = $paymentType['AccountNumber'];
                                    ?>
                                        <div class="row mb-3">
                                        <div class="payment-option border p-3 rounded" data-index="<?= $index ?>">
                                            <?php // Debugging output for troubleshooting
                                            // var_dump($_POST['paymentType'], $paymentTypeID);
                                            ?>
                                            <input type="radio" name="paymentType" id="pay-<?= $index ?>" value="<?= $paymentTypeID ?>" class="d-none" <?php if(isset($_POST['paymentType'])) { if($_POST['paymentType'] == $paymentTypeID) echo 'checked'; } ?>>
                                            <label for="pay-<?= $index ?>" class="d-block w-100 mb-0">
                                            <strong><?= $typeName ?></strong>
                                            </label>

                                            <div class="payment-details mt-2" style="display: none;">
                                            
                                            <p class="mb-0">Account No: <span class="text-secondary"><?= $accountNumber ?></span></p>
                                            <p class="mb-1">Account Name: <span class="text-secondary"><?= $accountName ?></span></p>
                                            </div>
                                        </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php
                                    if(isset($_POST['btnBook'])){
                                        if($validation['paymentTypeStatus']){
                                            echo '
                                            <div class="row">                                     
                                                <small class="text-danger ms-2">Choose one payment!</small>
                                            </div>
                                            ';
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="row mt-5 gx-0">
                                    <h6 class="mb-3">Attach the screenshot of your payment</h6>
                                    <input type="file" name="image" id="" class="form-control bg-light px-2" onchange="loadFile(event, 'output')">
                                    <?php
                                    if(isset($_POST['btnBook'])){
                                        if($validation['imageStatus']){
                                            echo '
                                            <div class="row">                                     
                                                <small class="text-danger ms-2">Screenshot is required!</small>
                                            </div>
                                            ';
                                        }
                                    }
                                    ?>
                                    <div class="row mt-2">
                                        <div class="">
                                        <img src="" id="output" class="w-100 img-thumbnail img-fluid output-img-small payment-img-preview"/>
                                        </div>
                                    </div>
                                
                                </div>

                                <button type="submit" name="btnBook" class="btn btn-primary fs-6 w-100 mt-2 d-none d-md-block">Book Now</button>

                            </div>
                        </div>
                    </div>
                    <!-- Left Content End -->
                    
                    <!-- Right Content Start -->
                    <div class="col">
                        <div class="position-sticky p-2" style="top: 0;">
                            <div class="summary-box border">
                                <div class="row flex-column flex-lg-row pt-4 px-4">
                                    <div class="col-6 col-md-5 col-lg-2">
                                        <img src="./../images/<?php echo $packageImage ?>" alt="" class="w-100 rounded">
                                    </div>
                                    <div class="col mt-2 mt-lg-0">
                                        <h5 class="fw-bold"><?php echo $title ?></h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="row px-4">
                                    <div class="row">
                                        <div class="col-1 ms-2">
                                        <i class="fa-solid fa-location-dot text-dark"></i>
                                        </div>
                                        <div class="col">
                                            <p class="text-dark">Destination: <?php echo $destination ?></p>
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
                                            <p class="text-dark">Duration: <?php echo $duration ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1 ms-2">
                                            <i class="fa-solid fa-globe text-dark"></i>
                                        </div>
                                        <div class="col">
                                            <p class="text-dark">Guided in <?php echo $languages ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1 ms-2">
                                            <i class="fa-regular fa-flag text-dark"></i>
                                        </div>
                                        <div class="col">
                                            <p class="text-dark">Guided by <?php echo $guide ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1 ms-2">
                                            <i class='fa-solid fa-people-group text-dark'></i>
                                        </div>
                                        <div class="col">
                                            <p class="text-dark <?php echo $availableTraveller == 1 ? 'd-none':'' ?>"><?php echo $availableTraveller ?> spaces left</p>
                                            <?php 
                                                if($availableTraveller == 1){
                                                    echo "<p class='text-danger'>Only $availableTraveller space left</p>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row px-4 mt-2">
                                    <div class="col">
                                        <h6>Total Traveler</h6>
                                    </div>
                                    <div class="col d-flex justify-content-end">
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-outline-secondary" id="decrease">-</button>
                                            
                                            <input type="text" id="totalPeople" name="totalPeople" class="form-control text-center mx-2" value="<?php echo $_POST['totalPeople'] ?? 1 ?>" style="width: 60px;" readonly>
                                            
                                            <button type="button" class="btn btn-outline-secondary" id="increase">+</button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if(isset($_POST['btnBook'])){
                                    if($validation['travelerStatus']){
                                        echo '
                                        <div class="row px-4"> 
                                                <small class="text-danger ms-2">Total travelers cannot exceed the remaining available spaces!</small>
                                        </div>
                                        ';
                                    }
                                }
                                ?>

                                <hr>

                                <div class="row px-4 mb-4">
                                    <div class="col">
                                        <h5 class="fw-bold">Total Price</h5>
                                    </div>
                                    <div class="col d-flex justify-content-end">
                                        <h5 class="fw-bold" id="totalPriceDisplay">฿<?php echo $_POST['totalPrice'] ?? $availabilityPrice ?></h5>
                                    </div>
                                </div>
                                
                                
                            <input type="hidden" name="totalPrice" id="totalPrice" value="<?php echo $_POST['totalPrice'] ?? $availabilityPrice ?>">
                            </div>
                            
                        </div>
                        
                    </div>
                    <!-- Right Content End -->
                    
                </div>
                <button type="submit" name="btnBook" class="btn btn-primary fs-6 w-100 mt-2 d-block d-md-none">Book Now</button>
                </form>

                <?php
                    if(isset($_POST['btnBook'])){
                        if(!in_array(true, $validation, true)) {
                            
                            // Bookig Data
                            $UserID = $_POST['userID'];
                            $AvailabilityID = $_POST['availabilityID'];
                            $BookingCode = $_POST['bookingCode'];
                            $FullName = $_POST['fullName'];
                            $Email = $_POST['email'];
                            $Phone = $_POST['phone'];
                    
                            $day = $_POST['dobDay'];
                            $month = $_POST['dobMonth'];
                            $year = $_POST['dobYear'];
                    
                            $DOB = sprintf('%04d-%02d-%02d', $year, $month, $day);//get the date of birth
                            $Gender = $_POST['gender'];
                            $TotalTraveller = $_POST['totalPeople'];
                            $BookingStatus = 'pending'; // default value
                    
                            // Payment Data
                            $PaymentTypeID = $_POST['paymentType'];
                            $TotalPrice = $_POST['totalPrice'];
                    
                            $imageName = uniqid() . $_FILES['image']['name'];
                            $tmpName = $_FILES['image']['tmp_name'];
                            $targetFile = "./../images/".$imageName;
                            move_uploaded_file($tmpName,$targetFile);
                    
                            $PaymentStatus = 'pending'; // default value
                    
                            try {
                                $connection->beginTransaction();
                    
                                $insertBooking = "INSERT INTO bookings(UserID, AvailabilityID, BookingCode, FullName, Email, Phone, DOB, Gender, TotalTraveller, BookingStatus) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $bookingInsertRes = $connection -> prepare($insertBooking);
                                $bookingInsertRes -> execute([$UserID, $AvailabilityID, $BookingCode, $FullName, $Email, $Phone, $DOB, $Gender, $TotalTraveller, $BookingStatus]);
                    
                                $BookingID = $connection->lastInsertId();
                    
                                $insertPayment = "INSERT INTO payments(BookingID, PaymentTypeID, TotalPrice, Screenshot, PaymentStatus) 
                                            VALUES (?, ?, ?, ?, ?)";
                                $paymentInsertRes = $connection -> prepare($insertPayment);
                                $paymentInsertRes -> execute([$BookingID, $PaymentTypeID, $TotalPrice, $imageName, $PaymentStatus]);
                    
                                $connection->commit();
                    
                                echo "<script>
                                            Swal.fire({
                                                title: 'Booked!',
                                                text: 'Thank you! You can check your booking status later.',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = './MyBooking.php';
                                                }
                                            });
                                        </script>";
                    
                            } catch (Exception $e) {
                                $connection->rollBack();
                                echo "Failed to create booking and payment: " . $e->getMessage();
                            }
                        }
                    }
                ?>

            </div>

        </div>   
    </div>
</body>
<script>
    $(document).ready(function () {
    var pricePerPerson = <?php echo $availabilityPrice; ?>;
    var totalPeopleInput = $("#totalPeople");
    var totalPriceInput = $("#totalPrice");
    var totalPriceDisplay = $("#totalPriceDisplay");

    $("#increase").click(function () {
        var current = parseInt(totalPeopleInput.val());
        totalPeopleInput.val(current + 1);
        updateTotal();
    });

    $("#decrease").click(function () {
        var current = parseInt(totalPeopleInput.val());
        if (current > 1) {
            totalPeopleInput.val(current - 1);
            updateTotal();
        }
    });

    function updateTotal() {
        var count = parseInt(totalPeopleInput.val());
        totalPriceInput.val(count * pricePerPerson);
        totalPriceDisplay.text(`฿${count * pricePerPerson}`);
    }

    // Set selected day, month, year if POST
        var selectedDay = <?php echo json_encode($_POST['dobDay'] ?? ''); ?>;
        var selectedMonth = <?php echo json_encode($_POST['dobMonth'] ?? ''); ?>;
        var selectedYear = <?php echo json_encode($_POST['dobYear'] ?? ''); ?>;
        if(selectedDay) $("#day").val(selectedDay);
        if(selectedMonth) $("#month").val(selectedMonth);
        if(selectedYear) $("#year").val(selectedYear);
});

</script>
<!-- js link -->
<script src="./app.js"></script>

<!-- bootstrap js link -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>