<?php
$title = "Registration Page";
ob_start();

require_once('../database/DbConnection.php');

if(isset($_POST['btnRegister'])){
    $validation = [
        'fullNameStatus' => false,
        'emailStatus' => false,
        'duplicateEmailStatus' => false,
        'passwordStatus' => false,
        'confirmPaswordStatus' => false,
    ];

    $validation['fullnameStatus'] = $_POST['fullname'] == "" ? true:false;
    $validation['emailStatus'] = $_POST['email'] == "" ? true:false;
    $validation['passwordStatus'] = $_POST['password'] == "" ? true:false;

    $email = $_POST['email'];

    $selectEmail = "SELECT COUNT(*) FROM users WHERE Email=?";
    $emailRes = $connection -> prepare($selectEmail);
    $emailRes -> execute([$email]);
    $emailCount = $emailRes -> fetchColumn();

    $validation['duplicateEmailStatus'] = $emailCount>0 ? true:false;
}
?>

<!-- Main Container Start -->
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-4 p-3 bg-white shadow box-area">
    <!-- Left Box Start -->
           <div class="col-md-6 d-none d-md-block left-box">
            <img src="../images/website_img_2.jpg" class="img-fluid w-100 h-100" style="width: 250px;">
           </div>
    <!-- Left Box End -->
    <!-- Right Box Start -->
       <div class="col-md-6 right-box">
            <div class="row align-items-center">
                <div class="mb-4">
                     <p class="logo">WanderWay</p>
                </div>
                <form action="Register.php" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg bg-light fs-6" name="fullname" placeholder="Fullname" value="<?php echo $_POST['fullname'] ?? ""; ?>">
                    </div>
                       <?php
                            if(isset($_POST['btnRegister'])){
                                if($validation['fullnameStatus']){
                                    echo "<div><small class='text-danger ms-2'>Fullname is required!</small></div>";
                                }
                            }
                        ?>
                    <div class="input-group mt-3">
                        <input type="text" class="form-control form-control-lg bg-light fs-6" name="email" placeholder="Email address" value="<?php echo $_POST['email'] ?? ""; ?>">
                    </div>
                       <?php
                            if(isset($_POST['btnRegister'])){
                                if($validation['emailStatus']){
                                    echo "<div><small class='text-danger ms-2'>Email is required!</small></div>";
                                }
                                    
    
                                if($validation['duplicateEmailStatus']){
                                    echo "<div><small class='text-danger ms-2'>Email already exists!</small></div>";
                                }
                                
                                
                            }
                        ?>
                    
                    <div class="input-group mt-3">
                        <input type="password" class="form-control form-control-lg bg-light fs-6" name="password" placeholder="Password" value="<?php echo $_POST['password'] ?? ""; ?>">
                    </div>
                        <?php
                            if(isset($_POST['btnRegister'])){
                                if($validation['passwordStatus']){
                                    echo "<div><small class='text-danger ms-2'>Password is required!</small></div>";
                                }
                            }
                        ?>
                    
                    <div class="input-group mt-3">
                        <input type="password" class="form-control form-control-lg bg-light fs-6" name="confirmPassword" placeholder="Confirm Password">
                    </div>
                    <?php
                            if(isset($_POST['btnRegister'])){
                                if(!$validation['fullnameStatus'] && !$validation['emailStatus'] && !$validation['passwordStatus'] && !$validation['duplicateEmailStatus']){
                                    if($_POST['password'] == $_POST['confirmPassword']){
                                        if(strlen($_POST['password']) >= 8){
                                            $fullname = $_POST['fullname'];
                                            $email = $_POST['email'];
                                            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                                            $insertUser = "INSERT INTO users(FullName,Email,Role,Password) VALUES(?,?,?,?)";
                                            $insertRes = $connection -> prepare($insertUser);
                                            $insertRes -> execute([$fullname,$email,'customer',$password]);
                                            
                                            echo "<script>
                                                        Swal.fire({
                                                            title: 'Done!',
                                                            text: 'Your account is created successfully.',
                                                            icon: 'success',
                                                            confirmButtonText: 'OK'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                window.location.href = './Login.php';
                                                            }
                                                        });
                                                    </script>";
                                        }else{
                                            echo "<div><small class='text-danger ms-2'>Password must has at least 8 characters!</small></div>";
                                        }
                                    }else{
                                        echo "<div><small class='text-danger ms-2'>Password do not match!</small></div>";
                                    }
                                    
                                }
                            }
                    ?>

                    <div class="input-group mt-3">
                        <button type="submit" class="btn btn-lg btn-primary w-100 fs-6" name="btnRegister">Sign Up</button>
                    </div>
                </form>
                <div class="mt-3">
                    <small>Already have an account? <a href="./Login.php">Login Here</a></small>
                </div>
            </div>
        </div> 
       <!-- Right Box End -->
    </div>
</div>


<?php
$body = ob_get_clean();
include('./layout/master.php');
?>