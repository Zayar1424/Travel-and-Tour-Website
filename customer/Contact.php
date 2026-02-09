<?php
$title = "Contact - WanderWay";
ob_start();
require_once('../database/DbConnection.php');

if(isset($_POST['btnSubmit'])){
     // Validation
     $validation = [
        'nameStatus' => false,
        'emailStatus' => false,
        'messageStatus' => false,
    ];

    $validation['nameStatus'] = $_POST['name'] == "" ? true:false;
    $validation['emailStatus'] = $_POST['email'] == "" ? true:false;
    $validation['messageStatus'] = $_POST['message'] == "" ? true:false;
}
?>

<div class="container-fluid px-0">
    <!-- Page Header Start -->
    <div class="page-header3 d-flex justify-content-center">
        <div class="pt-5">
            <h2>Contact</h2>
            <p class="text-center mt-4"><a href="./Home.php" class="text-decoration-none">Home</a>><span><u>Contact</u></span></p>
        </div>
    </div>
    <!-- Page Header End -->
    <!-- Contact Section Start -->
    <form action="Contact.php" method="POST">
        <div class="px-0 px-lg-5 pt-2 pt-lg-5">    
        </div>
        <div class="row px-4 px-lg-5 pt-4 gx-0 contact">
            
            <div class="col-12 col-md-6">
                <h4 class="fw-bold mt-5">Get In Touch</h4>
                <div class="mt-4">
                    <p>Have questions or need more information about our services? Feel free to reach out, and our friendly team will assist you every step of the way.</p>
                </div>
                <div class="ps-0 ps-lg-3 mt-5">
                    <div class="row mt-4">
                        <div class="col-1 pt-3 me-3">
                            <i class="fa-solid fa-location-arrow text-primary fs-1"></i>
                        </div>
                        <div class="col ps-3 ps-lg-4">
                            <p class="fw-bold">Head Office</p>
                            <p>123 Soi Sukhumvit 11,Khlong Toei Nuea, Watthana, Bangkok 10110, Thailand</p>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-1 pt-3 me-3">
                            <i class="fa-solid fa-phone text-primary fs-2"></i>
                        </div>
                        <div class="col ps-3 ps-lg-4">
                            <p class="fw-bold">Phone</p>
                            <p>0665896411</p>
                        </div>
                    </div>
                    <div class="row mt-4">
                    <div class="col-1 pt-3 me-3">
                        <i class="fa-solid fa-envelope text-primary fs-2"></i>
                    </div>
                    <div class="col ps-3 ps-lg-4">
                        <p class="fw-bold">Email</p>
                        <p>info@wanderway.com</p>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card mt-5 mt-md-2 shadow-sm input-form">
                    <div class="card-body px-3 px-lg-4 py-4">
                        <h5>Question? Send us a message!</h5>
                        <div class="p-1 p-lg-2">
                            <div class="mt-2">
                                <h6>Name</h6>
                                <input type="text" id="" name="name" class="form-control underline-input" placeholder="Enter your name" value="<?php echo $_POST['name'] ?? null ?>">
                            </div>
                            <?php
                                if(isset($_POST['btnSubmit'])){
                                    if($validation['nameStatus']){
                                        echo '
                                        <div class="row">                                     
                                            <small class="text-danger ms-2">Name is required!</small>
                                        </div>
                                        ';
                                    }
                                }
                            ?>
                            <div class="mt-2">
                                <h6>Email</h6>
                                <input type="email" id="" name="email" class="form-control underline-input" placeholder="Enter your email" value="<?php echo $_POST['email'] ?? null ?>">
                            </div>
                            <?php
                                if(isset($_POST['btnSubmit'])){
                                    if($validation['emailStatus']){
                                        echo '
                                        <div class="row">                                     
                                            <small class="text-danger ms-2">Email is required!</small>
                                        </div>
                                        ';
                                    }
                                }
                            ?>
                            <div class="mt-2">
                                <h6>Message</h6>
                                <textarea name="message" id="" class="form-control underline-input" style="height: 220px; resize: none; overflow-y: auto;" placeholder="Enter message"><?php echo $_POST['message'] ?? null ?></textarea>
                            </div>
                            <?php
                                if(isset($_POST['btnSubmit'])){
                                    if($validation['messageStatus']){
                                        echo '
                                        <div class="row">                                     
                                            <small class="text-danger ms-2">Message is required!</small>
                                        </div>
                                        ';
                                    }
                                }
                            ?>
                            <div class="mt-4">
                                <button type="submit" name="btnSubmit" class="btn btn-primary fs-6 w-100">Submit</button>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </form>
    <?php
        if(isset($_POST['btnSubmit'])){
            if(!in_array(true, $validation, true)) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $message = $_POST['message'];

                $insertContact = "INSERT INTO contacts(Name,Email,Message) VALUES(?,?,?)";
                $contactInsertRes = $connection -> prepare($insertContact);
                $contactInsertRes -> execute([$name,$email,$message]);

                echo "<script>
                            Swal.fire({
                                title: 'Submitted!',
                                text: 'Thank you for contacting us! We will reply via your email.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = './Contact.php';
                                }
                            });
                        </script>";
            }
        }
    ?>
    <!-- Contact Section End -->
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>