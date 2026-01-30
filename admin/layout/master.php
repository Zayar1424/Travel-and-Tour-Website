<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']); // Get current page filename
require_once('../database/DbConnection.php');

$id = $_SESSION['id'] ?? null;
$name = $_SESSION['fullname'] ?? null;
$email = $_SESSION['email'] ?? null;
$role = $_SESSION['role'] ?? null;
$profile = $_SESSION['profile'] ?? null;

if (!isset($id)) 
{
    echo "<script>window.alert('Something went wrong! Please login again..')</script>";
	echo "<script>window.location='./../auth/Login.php'</script>";  
}
if($role != 'admin' && $role != 'superadmin')
{
    echo "<script>window.location='./../customer/Home.php'</script>";  
}

// Destination Delete Success
$destinationDeleteMessage = isset($_SESSION['destination_delete_success']) ? $_SESSION['destination_delete_success'] : null;
unset($_SESSION['destination_delete_success']); // Remove session after using it

// Admin Delete Success
$adminDeleteMessage = isset($_SESSION['admin_delete_success']) ? $_SESSION['admin_delete_success'] : null;
unset($_SESSION['admin_delete_success']);

// Guide Delete Success
$guideDeleteMessage = isset($_SESSION['guide_delete_success']) ? $_SESSION['guide_delete_success'] : null;
unset($_SESSION['guide_delete_success']);

// Package Delete Success
$packageDeleteMessage = isset($_SESSION['package_delete_success']) ? $_SESSION['package_delete_success'] : null;
unset($_SESSION['package_delete_success']);

// Payment Type Delete Success
$typeDeleteMessage = isset($_SESSION['type_delete_success']) ? $_SESSION['type_delete_success'] : null;
unset($_SESSION['type_delete_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'WanderWay' ; '' ?></title>

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
<body>
    
        <div class="container-fluid">
    
            
             <div class="row">
                <!-- Side Bar Start -->
                <div class="col-2 side-bar">
                    <div class="row">
                        <p class="logo">WanderWay</p>
                    </div>
                    <div class="row">
                        <ul class="nav flex-column side-nav">
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'Dashboard.php') ? 'nav-active' : ''; ?>" href="./Dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'Package.php') ? 'nav-active' : ''; ?>" href="./Package.php">Packages</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'Destination.php') ? 'nav-active' : ''; ?>" href="./Destination.php">Destinations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'TourGuide.php') ? 'nav-active' : ''; ?>" href="./TourGuide.php">Tour Guides</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'PaymentType.php') ? 'nav-active' : ''; ?>" href="./PaymentType.php">Payment Types</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'Bookings.php') ? 'nav-active' : ''; ?>" href="./Bookings.php">All Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($current_page == 'Contact.php') ? 'nav-active' : ''; ?>" href="./Contact.php">Contacts</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Side Bar End -->
                <div class="col-10 right-main">
                <div class="right-top-bar shadow-sm">
                    <div class="row gx-0 pt-1"> 
                        <div class="col px-3 pt-2">
                            <h3>
                                <?php
                                if($current_page == 'Dashboard.php'){
                                    echo 'Dashboard';
                                }
                                if($current_page == 'Package.php'){
                                    echo 'Packages';
                                }
                                if($current_page == 'Destination.php'){
                                    echo 'Destinations';
                                }
                                if($current_page == 'TourGuide.php'){
                                    echo 'Tour Guides';
                                }
                                if($current_page == 'PaymentType.php'){
                                    echo 'Payment Types';
                                }
                                if($current_page == 'Bookings.php'){
                                    echo 'All Bookings';
                                }
                                if($current_page == 'Contact.php'){
                                    echo 'Contacts';
                                }
                                ?>
                            </h3>
                        </div>
                    
                        <div class="col d-flex align-items-center justify-content-end h-100">
                            <div class="row gx-0">
                                <div class="col d-flex align-items-center justify-content-end me-3">
                                    <img src="<?php echo $profile ? './../images/' . $profile : './../images/user_profile.jpg'; ?>" class="rounded-circle" width="50" height="50" alt="Profile Picture">
                                </div>
                                <div class="col">
                                    <div class="text-start admin-name"><?php echo $name; ?> <span class="text-start admin-role"><?php echo $role; ?></span></div>
                                </div>
                                <!-- Dropdown Menu -->
                                <div class="col-auto mt-3 me-2">
                                    <div class="dropdown down-button">
                                        <button class="btn btn-link text-decoration-none p-0" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end mt-4" aria-labelledby="profileDropdown">
                                            <?php
                                                if($role == 'superadmin'){
                                                    echo '
                                                    <li><a class="dropdown-item" href="./ManageAdmin.php"><i class="fa-solid fa-users-line me-3"></i>Admins</a></li>
                                                    ';
                                                }
                                            ?>
                                            <li><a class="dropdown-item" href="./EditProfile.php"><i class="fa-solid fa-user me-3 ms-1"></i>Edit Profile</a></li>
                                            <li><a class="dropdown-item" href="./ChangePassword.php"><i class="fa-solid fa-key me-3 ms-1"></i>Change Password</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="./Logout.php"><i class="fa-solid fa-right-from-bracket me-3 ms-1"></i>Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div>
                        <?php echo $content ?? ''; ?>
                    </div>
            </div>
                    
        </div>
    </div>
        
        <!-- Destination Delete Success -->
        <?php if ($destinationDeleteMessage): ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Done!',
                        text: '<?php echo addslashes($destinationDeleteMessage); ?>', // Avoid any JS errors with quotes in message
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                };
            </script>
        <?php endif; ?>
        
        <!-- Admin Delete Success -->
        <?php if ($adminDeleteMessage): ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Done!',
                        text: '<?php echo addslashes($adminDeleteMessage); ?>',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                };
            </script>
        <?php endif; ?>

        <!-- Guide Delete Success -->
        <?php if ($guideDeleteMessage): ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Done!',
                        text: '<?php echo addslashes($guideDeleteMessage); ?>',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                };
            </script>
        <?php endif; ?>

        <!-- Package Delete Success -->
        <?php if ($packageDeleteMessage): ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Done!',
                        text: '<?php echo addslashes($packageDeleteMessage); ?>',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                };
            </script>
        <?php endif; ?>

        <!-- Payment Type Delete Success -->
        <?php if ($typeDeleteMessage): ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Done!',
                        text: '<?php echo addslashes($typeDeleteMessage); ?>',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                };
            </script>
        <?php endif; ?>
                    
</body>

<!-- js link -->
<script src="./app.js"></script>

<!-- bootstrap js link -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>