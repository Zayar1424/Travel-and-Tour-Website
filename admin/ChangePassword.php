<?php
$title = "Change Password";
ob_start();
session_start();
require_once('../database/DbConnection.php');

$authID = $_SESSION['id'];
$authRole = $_SESSION['role'];

if(isset($_POST['btnUpdatePassword'])){
    $validation = [
        'oldPasswordStatus' => false,
        'oldPasswordCheck' => false,
        'newPasswordStatus' => false,
    ];

    $validation['oldPasswordStatus'] = $_POST['oldPassword'] == "" ? true:false;
    $validation['newPasswordStatus'] = $_POST['newPassword'] == "" ? true:false;

    $oldPasswordComplete = false;

    $oldPassword = $_POST['oldPassword'];

    $selectPassword = "SELECT Password FROM users WHERE UserID=?";
    $selectRes = $connection -> prepare($selectPassword);
    $selectRes -> execute([$authID]);
    $data = $selectRes -> fetch(PDO::FETCH_ASSOC);

    if($authRole == 'superadmin'){
        $validation['oldPasswordCheck'] = $oldPassword != $data['Password'] ? true:false;
    }

    if($authRole == 'admin'){
        $validation['oldPasswordCheck'] = !password_verify($oldPassword, $data['Password']) ? true:false;
    }

}
?>

<div class="container-fluid overflow-auto container-scroll">
    <div class="row mb-5">
        <div class="mt-3">
            <h4 class="ms-2">Change Password</h4>
        </div>

        <div class="col-6">
            <div class="card mt-2 shadow-sm input-form">
                <div class="card-body">
                    <form action="ChangePassword.php" method="POST">
                        <div class="row mt-2">
                            <div class="col-4">
                                <p>Old Password</p>
                            </div>
                            <div class="col">
                                <input type="password" name="oldPassword" id="" class="form-control bg-light" placeholder="Enter Old Password" value="<?php echo $_POST['oldPassword'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePassword'])){
                                if($validation['oldPasswordStatus']){
                                    echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Old password is required!</small>
                                            </div>
                                        </div>
                                        ';
                                }
                                    

                                    
                                else if($validation['oldPasswordCheck']){
                                    echo '
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Password is incorrect!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                    
                                else{
                                    $oldPasswordComplete = true;
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-4">
                                <p>New Password</p>
                            </div>
                            <div class="col">
                                <input type="password" name="newPassword" id="" class="form-control bg-light" placeholder="Enter New Password" value="">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePassword'])){
                                if($validation['newPasswordStatus']){
                                    echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">New password is required!</small>
                                            </div>
                                        </div>
                                        ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-4">
                                <p>Confirm Password</p>
                            </div>
                            <div class="col">
                                <input type="password" name="confirmPassword" id="" class="form-control bg-light" placeholder="Confirm Password" value="">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePassword'])){
                                if(!$validation['oldPasswordStatus'] && !$validation['newPasswordStatus'] && !$validation['oldPasswordCheck'] && $oldPasswordComplete){
                                    if($_POST['newPassword'] == $_POST['confirmPassword']){
                                        if(strlen($_POST['newPassword']) >= 8){
                                            $newPassword = $_POST['newPassword'];
                                            if($authRole != 'superadmin'){
                                                $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
                                            }
                                            
                                            
                                            $updatePassword = "UPDATE users SET Password=? WHERE UserID=?";
                                            $updateRes = $connection -> prepare($updatePassword);
                                            $updateRes -> execute([$newPassword,$authID]);

                                            echo "<script>
                                                        Swal.fire({
                                                            title: 'Done!',
                                                            text: 'Password changed successfully.',
                                                            icon: 'success',
                                                            confirmButtonText: 'OK'
                                                        }).then((result) => {
                                                        if (result.isConfirmed) {
                                                                window.location.href = './ChangePassword.php';
                                                            }
                                                        });
                                                    </script>";
                                        }else{
                                            echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Password must have at least 8 characters!</small>
                                            </div>
                                        </div>
                                        ';
                                        }
                                    }else{
                                        echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Password do not match!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                    
                                }
                            }
                        ?>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="btnUpdatePassword" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>