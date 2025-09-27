<?php
$title = "Edit Availability";
ob_start();
session_start();
require_once('../database/DbConnection.php');

$availabilityID = $_GET['availabilityID'] ?? $_POST['availabilityID'] ?? $_SESSION['availabilityID'] ?? null;

$selectAvailability = "SELECT * FROM availability WHERE AvailabilityID=?";
$selectRes = $connection -> prepare($selectAvailability);
$selectRes -> execute([$availabilityID]);
$availabilityData = $selectRes -> fetch(PDO::FETCH_ASSOC);

$packageID = $availabilityData['PackageID'];
$startDate = $availabilityData['StartDate'];
$endDate = $availabilityData['EndDate'];
$availabilityPrice = $availabilityData['Price'];

if(isset($_POST['btnUpdateAvailability'])){
$availabilityValidation = [
    'startDateStatus' => false,
    'endDateStatus' => false,
    'priceStatus' => false,
    'priceCheck' =>false,
];
    
$priceComplete = false;
    
$availabilityValidation['startDateStatus'] = $_POST['startDate'] == "" ? true:false;
$availabilityValidation['endDateStatus'] = $_POST['endDate'] == "" ? true:false;
$availabilityValidation['priceStatus'] = $_POST['availabilityPrice'] == "" ? true:false;
$availabilityValidation['priceCheck'] = $_POST['availabilityPrice'] < 0 ? true:false;
    }
?>


<div class="container-fluid overflow-auto container-scroll">
    <!-- Availability Form Start -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="mt-3">
                <h4 class="ms-2">Edit Availability</h4>
            </div>
            
            
            <div class="card mt-2 shadow-sm input-form">
                <div class="card-body">
                    <form action="UpdateAvailability.php" method="POST">
                        <input type="hidden" name="availabilityID" value="<?php echo $availabilityID; ?>">
                        <input type="hidden" name="packageID" value="<?php echo $packageID; ?>">
                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Start Date</p>
                            </div>
                            <div class="col">
                                    <input type="text" id="" name="startDate" class="form-control bg-light datepicker" value="<?php echo $_POST['startDate'] ?? $startDate; ?>">
                            </div>
                        </div>
                            <?php
                                if(isset($_POST['btnUpdateAvailability'])){
                                    if($availabilityValidation['startDateStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Start date is required!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }
                            ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>End Date</p>
                            </div>
                            <div class="col">
                                <input type="text" id="" name="endDate" class="form-control bg-light datepicker" value="<?php echo $_POST['endDate'] ?? $endDate; ?>">
                            </div>
                        </div>
                            <?php
                                if(isset($_POST['btnUpdateAvailability'])){
                                    if($availabilityValidation['endDateStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">End date is required!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }
                            ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Price</p>
                            </div>
                            <div class="col">
                                <input type="number" name="availabilityPrice" id="" class="form-control bg-light" value="<?php echo $_POST['availabilityPrice'] ?? $availabilityPrice; ?>">
                            </div>
                        </div>
                            <?php
                                if(isset($_POST['btnUpdateAvailability'])){
                                    if($availabilityValidation['priceStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Price is required!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                    else if($availabilityValidation['priceCheck']){
                                        echo '
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Please enter a valid number!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                    else{
                                        $priceComplete = true;
                                    }
                                }
                            ?>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="btnUpdateAvailability" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Save</button>
                        </div>

                            <?php
                                if(isset($_POST['btnUpdateAvailability'])){
                                    if(!$availabilityValidation['startDateStatus'] && !$availabilityValidation['endDateStatus'] && !$availabilityValidation['priceStatus'] && !$availabilityValidation['priceCheck'] && $priceComplete){
                                        $availabilityID = $_POST['availabilityID'];
                                        $packageID = $_POST['packageID'];
                                        $startDate = $_POST['startDate'];
                                        $endDate = $_POST['endDate'];
                                        $availabilityPrice = $_POST['availabilityPrice'];

                                        $updateAvailability = "UPDATE availability SET PackageID=?,StartDate=?,EndDate=?,Price=? WHERE AvailabilityID=?";
                                        $availabilityUpdateRes = $connection -> prepare($updateAvailability);
                                        $availabilityUpdateRes -> execute([$packageID,$startDate,$endDate,$availabilityPrice,$availabilityID]);

                                        $_SESSION['packageID'] = $packageID;
                                        $_SESSION['availabilityID'] = $availabilityID;
                                        
                                        echo "<script>
                                                    Swal.fire({
                                                        title: 'Done!',
                                                        text: 'Availability is updated successfully.',
                                                        icon: 'success',
                                                        confirmButtonText: 'OK'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            window.location.href = './PackageDetail.php?packageID=" . $packageID . "';
                                                        }
                                                    });
                                                </script>";
                                    }
                                    else{
                                        $_SESSION['availabilityID'] = $availabilityID;
                                    }
                                }
                            ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Availability Form End -->
    <!-- Booking Table Start -->
    <div class="row align-items-start mb-4">
        <div class="col">
                <div class="card mt-2 table-booking table-container">
                    <div class="card-body">
                        <h5>Booking List on '<?php echo $startDate ?>'</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Booking Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Traveler</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $selectBooking = "SELECT * FROM bookings WHERE AvailabilityID=?";                              
                                $bookingSelectRes = $connection->prepare($selectBooking);
                                $bookingSelectRes->execute([$availabilityID]);
                                $bookings = $bookingSelectRes->fetchAll(PDO::FETCH_ASSOC);
                                $bookingCount = count($bookings);
                                
                                if($bookingCount == 0){
                                    echo "No booking found.";
                                }

                                foreach($bookings as $item){
                                    $ID = $item['BookingID'];
                                    $code = $item['BookingCode'];
                                    $fullName = $item['FullName'];
                                    $bookingEmail = $item['Email'];
                                    $bookingPhone = $item['Phone'];
                                    $totalTraveler = $item['TotalTraveller'];
                                    $status = $item['BookingStatus'];
                                    echo "
                                        <tr>
                                            <td>$code</td>
                                            <td>$fullName</td>
                                            <td>$bookingEmail</td>
                                            <td>$bookingPhone</td>
                                            <td>$totalTraveler</td>
                                            ";
                                        if($status=='pending'){
                                            echo "<td class='text-primary'>Pending</td>";
                                        }
                                        if($status=='confirmed'){
                                            echo "<td class='text-success'>Confirmed</td>";
                                        }
                                        if($status=='cancelled'){
                                            echo "<td class='text-danger'>Cancelled</td>";
                                        }
                                    echo "
                                            <td class='text-end'><a href='./BookingDetail.php?bookingID=$ID' class='btn btn-sm btn-primary'><i class='fa-solid fa-circle-info'></i></a></td>
                                        </tr>
                                    ";
                                }
                            ?>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#"><</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
        </div>
    </div>
    <!-- Booking Table End -->
    
</div>
    
<?php
$content = ob_get_clean();
include('./layout/master.php');
?>