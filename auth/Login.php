<?php
$title = "Login Page";
ob_start();

session_start();
require_once('../database/DbConnection.php');

$id = $_SESSION['id'] ?? null;

if (isset($id)) 
{
    echo "<script>window.location='./../customer/Home.php'</script>";  
}

if(isset($_POST['btnLogin'])){
    $validation = [
        'emailStatus' => false,
        'passwordStatus' => false,
    ];

    $validation['emailStatus'] = $_POST['email'] == "" ? true:false;
    $validation['passwordStatus'] = $_POST['password'] == "" ? true:false;
}

?>

<!-- Main Container Start -->
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-4 p-3 bg-white shadow box-area">
    <!-- Left Box Start -->
           <div class="col-md-6 d-none d-md-block left-box">
            <img src="./../images/website_img_1.jpg" class="img-fluid w-100 h-100" style="width: 250px;">
           </div>
    <!-- Left Box End -->
    <!-- Right Box Start -->
       <div class="col-md-6 right-box">
            <div class="row align-items-center">
                <div class="mb-4">
                     <p class="logo">WanderWay</p>
                </div>
                <form action="Login.php" method="POST">
                    <div class="input-group">
                        <input type="email" class="form-control form-control-lg bg-light fs-6" name="email" placeholder="Email Address" value="<?php echo $_POST['email'] ?? ""; ?>">
                    </div>
                        <?php
                            if(isset($_POST['btnLogin'])){
                                if($validation['emailStatus']){
                                    echo "<div><small class='text-danger ms-2'>Email is required!</small></div>";
                                }
                            }
                        ?>

                    <div class="input-group mt-3">
                        <input type="password" class="form-control form-control-lg bg-light fs-6" name="password" placeholder="Password" value="<?php echo $_POST['password'] ?? ""; ?>">
                    </div>
                        <?php
                            if(isset($_POST['btnLogin'])){
                                if($validation['passwordStatus']){
                                    echo "<div><small class='text-danger ms-2'>Password is required!</small></div>";
                                }
                                if(!$validation['emailStatus'] && !$validation['passwordStatus']){
                                    $selectEmail = "SELECT * FROM users WHERE email=?";
                                    $emailRes = $connection -> prepare($selectEmail);
                                    $emailRes -> execute([$_POST['email']]);
                                    $userArray = $emailRes->fetch(PDO::FETCH_ASSOC);
                                    $emailCount = $emailRes -> rowCount();
                                    
                                    if ($emailCount > 0) {
                                        $isSuperAdmin = $userArray['Role'] === 'superadmin';
                                        $isPasswordCorrect = $isSuperAdmin
                                            ? $_POST['password'] === $userArray['Password']
                                            : password_verify($_POST['password'], $userArray['Password']);
                                    
                                        if ($isPasswordCorrect) {
                                            $_SESSION['id'] = $userArray['UserID'];
                                            $_SESSION['fullname'] = $userArray['FullName'];
                                            $_SESSION['email'] = $userArray['Email'];
                                            $_SESSION['role'] = $userArray['Role'];
                                            $_SESSION['profile'] = $userArray['ProfileImage'] ?? null;
                                            $_SESSION['phone'] = $userArray['Phone'] ?? null;
                                    
                                            $redirectPage = ($userArray['Role'] === 'superadmin' || $userArray['Role'] === 'admin')
                                                ? './../admin/Dashboard.php'
                                                : './../customer/Home.php';
                                    
                                            echo "<script>
                                                Swal.fire({
                                                    title: 'Done!',
                                                    text: 'Logged in successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '$redirectPage';
                                                    }
                                                });
                                            </script>";
                                        } else {
                                            echo "<div><small class='text-danger ms-2'>Email or password is incorrect!</small></div>";
                                        }
                                    } else {
                                        echo "<div><small class='text-danger ms-2'>Email or password is incorrect!</small></div>";
                                    }
                                    
                                }
                            }
                        ?>
                    <div class="input-group mt-3">
                        <button type="submit" class="btn btn-lg btn-primary w-100 fs-6" name="btnLogin">Login</button>
                    </div>
                </form>
                <div class="mt-3">
                    <small>Don't have an account? <a href="./Register.php">Sign Up</a></small>
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