<?php
$title = "Bookings";
ob_start();
require_once('../database/DbConnection.php');

// Search value
$searchKey = isset($_GET['searchKey']) ? $_GET['searchKey'] : "";
// Sort value
$sort = isset($_GET['sort']) ? $_GET['sort'] : "latest";

    // Define sorting conditions
    $orderBy = "b.CreatedAt DESC";
    if ($sort == "start_asc") {
        $orderBy = "a.StartDate ASC";
    } elseif ($sort == "start_desc") {
        $orderBy = "a.StartDate DESC";
    }
?>

<div class="container-fluid overflow-auto container-scroll">
    <div class="row mt-4">
        <div class="col">
        <form action="Bookings.php" method="GET" class="row gx-0">
            <div class="col">
                <input type="search" name="searchKey" placeholder="Enter Booking Code" class="form-control" value="<?php echo !empty($_GET['searchKey']) ? $_GET['searchKey'] : '' ?>">
            </div>
            <div class="col ms-2">
                <button class="btn btn-primary rounded" type="submit">Search</button>
            </div>
        </form>
        </div>
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
                                            echo "<span>Latest</span>";
                                        }
                                        elseif($_GET['sort'] == "start_asc"){
                                            echo "<span>Start Date <i class='fa-solid fa-arrow-down'></i></span>";
                                        }
                                        elseif($_GET['sort'] == "start_desc"){
                                            echo "<span>Start Date <i class='fa-solid fa-arrow-up'></i></span>";
                                        }
                                    }else{
                                        echo "Latest";
                                    }
                                ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item sort-option" href="#" data-sort="latest">Latest</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="start_asc">Start Date <i class="fa-solid fa-arrow-down"></i></a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="start_desc">Start Date <i class="fa-solid fa-arrow-up"></i></a></li>
                    </ul>
                </div>

            </div>
        </div>
        </div>
    </div>
    <?php $filterStatus = $_GET['status'] ?? ''; ?>
    <a href="Bookings.php"><button class="btn <?php echo $filterStatus == '' ? 'btn-primary':'btn-outline-primary' ?> btn-sm btn-rounded me-1">All</button></a>
    <a href="Bookings.php?status=pending"><button class="btn <?php echo $filterStatus == 'pending' ? 'btn-primary':'btn-outline-primary' ?> btn-sm btn-rounded me-1">Pending</button></a>
    <a href="Bookings.php?status=confirmed"><button class="btn <?php echo $filterStatus == 'confirmed' ? 'btn-primary':'btn-outline-primary' ?> btn-sm btn-rounded me-1">Confirmed</button></a>
    <a href="Bookings.php?status=cancelled"><button class="btn <?php echo $filterStatus == 'cancelled' ? 'btn-primary':'btn-outline-primary' ?> btn-sm btn-rounded me-1">Cancelled</button></a>
    <!-- Booking Table Start -->
    <div class="row align-items-start mb-4">
        <div class="col">
                <div class="card mt-2 table-booking table-container">
                    <div class="card-body">
                        <h5>Booking List</h5>
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
                                WHERE b.AvailabilityID = a.AvailabilityID";
              
                                $params = [];
                                
                                if (isset($_GET['searchKey']) && !empty($_GET['searchKey'])) {
                                    $selectBooking .= " AND b.BookingCode = ?";
                                    $params[] = $_GET['searchKey'];
                                }

                                if (isset($_GET['status']) && !empty($_GET['status'])) {
                                    $selectBooking .= " AND b.BookingStatus = ?";
                                    $params[] = $_GET['status'];
                                }
                                
                                // Append ORDER BY at the end
                                $selectBooking .= " ORDER BY $orderBy";
                                
                                $selectRes = $connection->prepare($selectBooking);
                                $selectRes->execute($params);
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