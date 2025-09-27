<?php
$title = "Admins";
ob_start();
session_start();
require_once('../database/DbConnection.php');

if(isset($_POST['btnAddAdmin'])){
    $validation = [
        'adminNameStatus' => false,
        'emailStatus' => false,
        'duplicateEmailStatus' => false,
        'phoneStatus' => false,
    ];
    
    $validation['adminNameStatus'] = $_POST['adminName'] == "" ? true:false;
    $validation['emailStatus'] = $_POST['email'] == "" ? true:false;
    $validation['phoneStatus'] = $_POST['phone'] == "" ? true:false;

    $email = $_POST['email'];
        
    $checkAdminEmail = "SELECT COUNT(*) FROM users WHERE Email=?";
    $checkRes = $connection -> prepare($checkAdminEmail);
    $checkRes -> execute([$email]);
    $userCount = $checkRes -> fetchColumn();
    
    $validation['duplicateEmailStatus'] = $userCount>0 ? true:false;
    }
?>

<div class="container-fluid overflow-auto container-scroll">
    <!-- Admin Form Start -->
    <div class="row">
        <div class="mt-3">
            <h4 class="ms-2">Admin Management</h4>
        </div>
        <div class="col-5">
                <div class="card mt-2 shadow-sm input-form">
                    <div class="card-body">
                        <form action="ManageAdmin.php" method="POST" enctype="multipart/form-data">
                            <div class="row mt-2">
                                <div class="col-4">
                                    <p>Admin Name</p>
                                </div>
                                <div class="col">
                                    <input type="text" name="adminName" id="" class="form-control bg-light" placeholder="Enter Admin Name" value="<?php echo $_POST['adminName'] ?? ""; ?>">
                                </div>
                            </div>
                            <?php
                                if(isset($_POST['btnAddAdmin'])){
                                    if($validation['adminNameStatus']){
                                        echo '
                                        <div class="row">
                                            <div class="col-4"></div>
                                            <div class="col">
                                                <small class="text-danger ms-2">Admin name is required!</small>
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
                                <input type="text" name="email" id="" class="form-control bg-light" placeholder="Enter Email" value="<?php echo $_POST['email'] ?? ""; ?>">
                                </div>
                            </div>
                            <?php
                                if(isset($_POST['btnAddAdmin'])){
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
                                <input type="text" name="phone" id="" class="form-control bg-light" placeholder="Enter Phone Number" value="<?php echo $_POST['phone'] ?? ""; ?>">
                                </div>
                            </div>
                            <?php
                                if(isset($_POST['btnAddAdmin'])){
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
                                    <p>Password</p>
                                </div>
                                <div class="col">
                                <input type="text" name="password" id="" class="form-control bg-light" placeholder="" value="admin12345#" readonly/>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" name="btnAddAdmin" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Add</button>
                            </div>

                            <?php
                            if(isset($_POST['btnAddAdmin'])){
                                if(!$validation['adminNameStatus'] && !$validation['emailStatus'] && !$validation['phoneStatus'] && !$validation['duplicateEmailStatus']){
                                    $adminName = $_POST['adminName'];
                                    $email = $_POST['email'];
                                    $phone = $_POST['phone'];
                                    
                                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                                    
                                    
                                
                                    $insertAdmin = "INSERT INTO users(FullName,Email,Phone,Role,Password) VALUE(?,?,?,?,?)";
                                    $insertRes = $connection -> prepare($insertAdmin);
                                    $insertRes -> execute([$adminName,$email,$phone,'admin',$password]);
                                
                                    echo "<script>
                                                Swal.fire({
                                                    title: 'Done!',
                                                    text: 'New admin is added successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                if (result.isConfirmed) {
                                                        window.location.href = './ManageAdmin.php';
                                                    }
                                                });
                                            </script>";
                                }
                            }
                            ?>
                            
                        </form>
                        
                    </div>
                </div>
        </div>
    </div>
    <!-- Admin Form End -->
    <!-- Admin Table Start -->
    <div class="row mb-4">
        <div class="col">
                <div class="card mt-4 table-admin table-container">
                    <div class="card-body">
                        <h5>Admin List</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $selectAdmin = "SELECT UserID,FullName,Email,Phone,Address,Role FROM users WHERE Role IN(?,?)";
                                $selectRes = $connection -> prepare($selectAdmin);
                                $selectRes -> execute(['superadmin','admin']);
                                $admins = $selectRes->fetchAll(PDO::FETCH_ASSOC);

                                foreach($admins as $item){
                                    $ID = $item['UserID'];
                                    $adminName = $item['FullName'];
                                    $adminEmail = $item['Email'];
                                    $adminPhone = $item['Phone'];
                                    $adminAddress = $item['Address'];
                                    $role = $item['Role'];
                                    echo "
                                        <tr>
                                            <td>$adminName</td>
                                            <td>$adminEmail</td>
                                            <td>$adminPhone</td>
                                            <td>$adminAddress</td>
                                            <td>$role</td>
                                            <td>
                                            ";

                                            if($role != $_SESSION['role']){
                                                echo "
                                                    <a href='./DeleteAdmin.php?adminID=$ID' class='btn btn-sm btn-danger'><i class='fa-solid fa-trash'></i></a>
                                                ";
                                            }
                                            
                                    echo "</td></tr>";
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
    <!-- Admin Table End -->
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>