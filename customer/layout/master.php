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

// Review delete message
$reviewDeleteMessage = isset($_SESSION['review_delete_success']) ? $_SESSION['review_delete_success'] : null;
unset($_SESSION['review_delete_success']); // Remove session after using it
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
    <nav class="navbar navbar-expand-md navbar-light bg-light px-0 px-lg-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="./Home.php"><p class="logo">WanderWay</p></a>

            <!-- hamburger menu -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
                <div class="collapse navbar-collapse gx-0 justify-content-lg-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                        <a class="nav-link" aria-current="" href="./Home.php">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./Package.php">Packages</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./About.php">About Us</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./Contact.php">Contact</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./MyBooking.php">Bookings</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./Profile.php">Profile</a>
                        </li>
                    </ul>  
                </div>   
        </div>
    </nav>

    <div>
        <?php echo $content ?? ''; ?>
    </div>

    <!-- Review Delete Success -->
        <?php if ($reviewDeleteMessage): ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        title: 'Done!',
                        text: '<?php echo addslashes($reviewDeleteMessage); ?>', // Avoid any JS errors with quotes in message
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                };
            </script>
        <?php endif; ?>
</body>

<footer class="mt-5">
    <div class="row gx-0 px-5 pt-5 pb-3 footer">
        <div class="col-12 col-md-4 footer-col pt-2 mb-4">
            <h5 class="fw-bold mb-4">Useful Links</h5>
            <div class="mb-2">
                <a href="./Home.php" class="text-decoration-none">Home</a>
            </div>
            <div class="mb-2">
                <a href="./Package.php" class="text-decoration-none">Packages</a>
            </div>
            <div class="mb-2">
                <a href="./About.php" class="text-decoration-none">About Us</a>
            </div>
            <div class="mb-2">
                <a href="/.Contact.php" class="text-decoration-none">Contact</a>
            </div>
            <div class="mb-2">
                <a href="./MyBooking.php" class="text-decoration-none">Bookings</a>
            </div>
            <div class="mb-2">
                <a href="./Profile.php" class="text-decoration-none">Profile</a>
            </div>      
        </div>
        <div class="col-12 col-md-4 mb-4">
            <a class="" href="./Home.php"><p class="logo mb-4">WanderWay</p></a>
            <div class="row">
                <div class="col-1 pt-3 me-2">
                    <i class="fa-solid fa-phone text-primary fs-3"></i>
                </div>
                <div class="col ps-4">
                    <p class="fw-bold">Phone</p>
                    <p>0665896411</p>
                </div>
            </div>
            <div class="row">
                <div class="col-1 pt-3 me-2">
                    <i class="fa-solid fa-envelope text-primary fs-3"></i>
                </div>
                <div class="col ps-4">
                    <p class="fw-bold">Email</p>
                    <p>info@wanderway.com</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 pt-2 mb-4">
            <h5 class="fw-bold mb-4">Follow Us</h5>
            <div class="d-flex">
                <div class="me-3">
                    <a href=""><i class="fa-brands fa-facebook text-decoration-none text-primary fs-4"></i></a>
                </div>
                <div class="me-3">
                    <a href=""><i class="fa-brands fa-instagram text-decoration-none text-primary fs-4"></i></a>
                </div>
                <div class="me-3">
                    <a href=""><i class="fa-brands fa-x-twitter text-decoration-none text-primary fs-4"></i></a>
                </div>
                <div class="me-3">
                    <a href=""><i class="fa-brands fa-linkedin text-decoration-none text-primary fs-4"></i></a>
                </div>
                <div class="me-3">
                    <a href=""><i class="fa-brands fa-youtube text-decoration-none text-primary fs-4"></i></a>
                </div>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-center">
            <span class="">
                &copy;2025 WanderWay | All Right Reserved
            </span>
        </div>
    </div>
    
</footer>

<!-- js link -->
<script src="./app.js"></script>

<!-- bootstrap js link -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>