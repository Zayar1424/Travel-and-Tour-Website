<?php
$title = "Admin Dashboard";
ob_start();
require_once('../database/DbConnection.php');

// Count total booking 
$selectTotalBookings = "SELECT COUNT(*) FROM bookings";
$totalBookingsRes = $connection -> prepare($selectTotalBookings);
$totalBookingsRes -> execute();
$totalBookingsCount = $totalBookingsRes -> fetchColumn();

// Count total packages
$selectTotalPackages = "SELECT COUNT(*) FROM packages";
$totalPackagesRes = $connection -> prepare($selectTotalPackages);
$totalPackagesRes -> execute();
$totalPackagesCount = $totalPackagesRes -> fetchColumn();

// Count total earnings
$selectTotalPrice = "SELECT COALESCE(SUM(TotalPrice), 0) AS TotalPrice FROM payments WHERE PaymentStatus=?";
$totalPriceRes = $connection -> prepare($selectTotalPrice);
$totalPriceRes -> execute(['confirmed']);
$totalPrice = $totalPriceRes -> fetch(PDO::FETCH_ASSOC);

// Count confirmed bookings
$selectConfirmedBookings = "SELECT COUNT(*) FROM bookings WHERE BookingStatus=?";
$confirmedBookingsRes = $connection -> prepare($selectConfirmedBookings);
$confirmedBookingsRes -> execute(['confirmed']);
$confirmedBookingsCount = $confirmedBookingsRes -> fetchColumn();

// Count pending bookings
$selectPendingBookings = "SELECT COUNT(*) FROM bookings WHERE BookingStatus=?";
$pendingBookingsRes = $connection -> prepare($selectPendingBookings);
$pendingBookingsRes -> execute(['pending']);
$pendingBookingsCount = $pendingBookingsRes -> fetchColumn();

// Count cancelled bookings
$selectCancelledBookings = "SELECT COUNT(*) FROM bookings WHERE BookingStatus=?";
$cancelledBookingsRes = $connection -> prepare($selectCancelledBookings);
$cancelledBookingsRes -> execute(['cancelled']);
$cancelledBookingsCount = $cancelledBookingsRes -> fetchColumn();
?>

<div class="container-fluid overflow-auto container-scroll">
    <!-- Report Session Start -->
    <div class="row py-4">
        <div class="col px-5">
            <div class="dashboard-card rounded p-2 row">
                <div class="col-3 d-flex align-items-center">
                    <div class="w-100 d-flex justify-content-center align-items-center"><i class="fa-regular fa-bookmark fs-1 text-primary"></i></div>
                </div>
                <div class="col ps-1 pt-1">
                    <p>Total Bookings</p>
                    <h4 class="fw-bold"><?php echo $totalBookingsCount ?></h4>
                </div>
            </div>   
        </div>
        <div class="col px-5">
            <div class="dashboard-card rounded p-2 row">
                <div class="col-3 d-flex align-items-center">
                    <div class="w-100 d-flex justify-content-center align-items-center"><i class="fa-regular fa-map fs-1 text-primary"></i></div>
                </div>
                <div class="col ps-1 pt-1">
                    <p>Total Packages</p>
                    <h4 class="fw-bold"><?php echo $totalPackagesCount ?></h4>
                </div>
            </div>   
        </div>
        <div class="col px-5">
            <div class="dashboard-card rounded p-2 row">
                <div class="col-3 d-flex align-items-center">
                    <div class="w-100 d-flex justify-content-center align-items-center"><i class="fa-solid fa-coins fs-1 text-primary"></i></div>
                </div>
                <div class="col ps-1 pt-1">
                    <p>Total Earnings</p>
                    <h4 class="fw-bold">฿<?php echo $totalPrice['TotalPrice'] ?></h4>
                </div>
            </div>    
        </div>
    </div>
    <div class="row py-4">
        <div class="col px-5">
            <div class="dashboard-card rounded p-2 row">
                <div class="col-3 d-flex align-items-center">
                    <div class="w-100 d-flex justify-content-center align-items-center"><i class="fa-regular fa-circle-check fs-1 text-success"></i></div>
                </div>
                <div class="col ps-1 pt-1">
                    <p>Confirmed Bookings</p>
                    <h4 class="fw-bold"><?php echo $confirmedBookingsCount ?></h4>
                </div>
            </div>   
        </div>
        <div class="col px-5">
            <div class="dashboard-card rounded p-2 row">
                <div class="col-3 d-flex align-items-center">
                    <div class="w-100 d-flex justify-content-center align-items-center"><i class="fa-solid fa-spinner fs-1 text-primary"></i></div>
                </div>
                <div class="col ps-1 pt-1">
                    <p>Pending Bookings</p>
                    <h4 class="fw-bold"><?php echo $pendingBookingsCount ?></h4>
                </div>
            </div>   
        </div>
        <div class="col px-5">
            <div class="dashboard-card rounded p-2 row">
                <div class="col-3 d-flex align-items-center">
                    <div class="w-100 d-flex justify-content-center align-items-center"><i class="fa-regular fa-circle-xmark fs-1 text-danger"></i></div>
                </div>
                <div class="col ps-1 pt-1">
                    <p>Cancelled Bookings</p>
                    <h4 class="fw-bold"><?php echo $cancelledBookingsCount ?></h4>
                </div>
            </div>    
        </div>
    </div>
    <!-- Report Session End -->
     <!-- Booking Table Start -->
    <div class="row align-items-start mb-4">
        <div class="col">
                <div class="card mt-2 table-booking">
                    <div class="card-body">
                        <div class="row">
                            <div class="col"><h5>Recent Bookings</h5></div>
                            <div class="col d-flex justify-content-end">
                                <a href="./Bookings.php" class="btn btn-primary btn-sm">See More</a>
                            </div>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Booking Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $selectBooking = "SELECT b.*, a.StartDate FROM bookings b, availability a
                                                WHERE b.AvailabilityID = a.AvailabilityID
                                                ORDER BY CreatedAt DESC LIMIT 5";                                
                                $selectRes = $connection->prepare($selectBooking);
                                $selectRes->execute();
                                $bookings = $selectRes->fetchAll(PDO::FETCH_ASSOC);
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
                                    $startDate = $item['StartDate'];
                                    $status = $item['BookingStatus'];
                                    echo "
                                        <tr>
                                            <td>$code</td>
                                            <td>$fullName</td>
                                            <td>$bookingEmail</td>
                                            <td>$bookingPhone</td>
                                            <td>$startDate</td>
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