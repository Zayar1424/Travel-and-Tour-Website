<?php
$title = "Edit Profile";
ob_start();
session_start();
require_once('../database/DbConnection.php');

$authID = $_SESSION['id'];
$authProfile = $_SESSION['profile']?? null;

$selectAdmin = "SELECT * FROM users WHERE UserID=?";
$selectRes = $connection -> prepare($selectAdmin);
$selectRes -> execute([$authID]);
$data = $selectRes -> fetch(PDO::FETCH_ASSOC);

$name = $data['FullName'];
$email = $data['Email'];
$phone = $data['Phone'];
$address = $data['Address'];
$image = $data['ProfileImage'];

if(isset($_POST['btnUpdateAdmin'])){
    $validation = [
        'nameStatus' => false,
        'emailStatus' => false,
        'duplicateEmailStatus' => false,
        'phoneStatus' => false,
        'addressStatus' => false,
    ];
    
    $validation['nameStatus'] = $_POST['name'] == "" ? true:false;
    $validation['emailStatus'] = $_POST['email'] == "" ? true:false;
    $validation['phoneStatus'] = $_POST['phone'] == "" ? true:false;
    $validation['addressStatus'] = $_POST['address'] == "" ? true:false;

    $email = $_POST['email'];
        
    $checkAdminEmail = "SELECT COUNT(*) FROM users WHERE Email=? AND UserID != ?"; // keep the same email
    $checkRes = $connection -> prepare($checkAdminEmail);
    $checkRes -> execute([$email,$authID]);
    $userCount = $checkRes -> fetchColumn();

    $validation['duplicateEmailStatus'] = $userCount>0 ? true:false;

    if(!$validation['nameStatus'] && !$validation['emailStatus'] && !$validation['phoneStatus'] && !$validation['addressStatus'] && !$validation['duplicateEmailStatus']){
    
        $newName=$_POST['name'];
        $newEmail=$_POST['email'];
        $newPhone=$_POST['phone'];
        $newAddress=$_POST['address'];
        if($_FILES['image']['name'] == "") // users don't choose image file
        {
            $updateAdmin = "UPDATE users SET FullName=?, Email=?, Phone=?, Address=? WHERE UserID=?";
            $updateRes = $connection -> prepare($updateAdmin);
            $updateRes -> execute([$newName,$newEmail,$newPhone,$newAddress,$authID]);
        }else{
            if (!empty($authProfile) && file_exists("./../images/" . $authProfile)) {
            unlink("./../images/$authProfile");
            }
            $imageName = uniqid() . $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            $targetFile = "./../images/" . $imageName;
    
            move_uploaded_file($tmpName, $targetFile);
    
            $updateAdmin = "UPDATE users SET FullName=?, Email=?, Phone=?, Address=?, ProfileImage=? WHERE UserID=?";
            $updateRes = $connection -> prepare($updateAdmin);
            $updateRes -> execute([$newName,$newEmail,$newPhone,$newAddress,$imageName,$authID]);
            $_SESSION['profile'] = $imageName;
        }
        echo "<script>
                    Swal.fire({
                        title: 'Done!',
                        text: 'Saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                    if (result.isConfirmed) {
                            window.location.href = './EditProfile.php';
                        }
                    });
                </script>";
    }
    }
?>

<div class="container-fluid overflow-auto container-scroll">
    <!-- Admin Form Start -->
    <div class="row mb-5">
        <div class="mt-3">
            <h4 class="ms-2">Your Profile</h4>
        </div>
        <div class="col-5">
                <div class="card mt-2 shadow-sm input-form">
                    <div class="card-body">
                        <form action="EditProfile.php" method="POST" enctype="multipart/form-data">
                        <img src="./../images/<?php echo $image ? $image : './../images/user_profile.jpg'; ?>" class="img-thumbnail mt-2 mb-2 rounded-circle admin-profile" style="width: 250px; height: 250px; margin-left:75px" id="output">
                            <div class="row mt-1">
                                <div class="col-4">
                                    <p>Profile</p>
                                </div>
                                <div class="col">
                                <input type="file" name="image" id="" class="form-control" onchange="loadFile(event, 'output')">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-4">
                                    <p>Name</p>
                                </div>
                                <div class="col">
                                    <input type="text" name="name" id="" class="form-control bg-light" value="<?php echo $_POST['name'] ?? $name; ?>">
                                </div>
                            </div>
                            <?php
                                if(isset($_POST['btnUpdateAdmin'])){
                                    if($validation['nameStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Name is required!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }
                            ?>
                            
            
                            <div class="row mt-2">
                                <div class="col-4">
                                    <p>Email</p>
                                </div>
                                <div class="col">
                                <input type="text" name="email" id="" class="form-control bg-light" value="<?php echo $_POST['email'] ?? $email; ?>">
                                </div>
                            </div>
                            <?php
                                if(isset($_POST['btnUpdateAdmin'])){
                                    if($validation['emailStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Email is required!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                    
                                    
                                        
        
                                        if($validation['duplicateEmailStatus']){
                                            echo '
                                            <div class="row">
                                                <div class="col-4"></div>
                                                <div class="col">
                                                    <small class="text-danger ms-2">Email already exists!</small>
                                                </div>
                                            </div>
                                            ';
                                        }
                                    
                                }
                            ?>

                            <div class="row mt-2">
                                <div class="col-4">
                                    <p>Phone</p>
                                </div>
                                <div class="col">
                                <input type="text" name="phone" id="" class="form-control bg-light" value="<?php echo $_POST['phone'] ?? $phone; ?>">
                                </div>
                            </div>
                            <?php
                                if(isset($_POST['btnUpdateAdmin'])){
                                    if($validation['phoneStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Phone number is required!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }
                            ?>

                            <div class="row mt-2">
                                <div class="col-4">
                                    <p>Address</p>
                                </div>
                                <div class="col">
                                <input type="text" name="address" id="" class="form-control bg-light" value="<?php echo $_POST['address'] ?? $address; ?>">
                                </div>
                            </div>
                            <?php
                                if(isset($_POST['btnUpdateAdmin'])){
                                    if($validation['addressStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Address is required!</small>
                                            </div>
                                        </div>
                                        ';
                                    }
                                }
                            ?>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" name="btnUpdateAdmin" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Save</button>
                            </div>
                            
                        </form>
                        
                    </div>
                </div>
        </div>
    </div>
    <!-- Admin Form End -->
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>