<?php
$title = "Tour Guides";
ob_start();
session_start();
require_once('../database/DbConnection.php');

if(isset($_POST['btnAddGuide'])){
$validation = [
    'guideNameStatus' => false,
    'duplicateNameStatus' => false,
    'emailStatus' => false,
    'phoneStatus' => false,
    'languagesStatus' => false,
];

$validation['guideNameStatus'] = $_POST['guideName'] == "" ? true:false;
$validation['emailStatus'] = $_POST['email'] == "" ? true:false;
$validation['phoneStatus'] = $_POST['phone'] == "" ? true:false;
$validation['languagesStatus'] = $_POST['languages'] == "" ? true:false;

$guideName = $_POST['guideName'];
    
$checkGuide = "SELECT COUNT(*) FROM tour_guides WHERE GuideName=?";
$checkRes = $connection -> prepare($checkGuide);
$checkRes -> execute([$guideName]);
$guideCount = $checkRes -> fetchColumn();

$validation['duplicateNameStatus'] = $guideCount>0 ? true:false;
}
?>

<div class="container-fluid overflow-auto container-scroll">
    <div class="row align-items-start mb-4">
        <!-- Left Form Start -->
        <div class="col-5">
            <div class="card mt-5 shadow-sm input-form">
                <div class="card-body">
                    <form action="TourGuide.php" method="POST" enctype="multipart/form-data">
                        <div class="row mt-2">
                            <div class="col-4">
                                <p>Guide Name</p>
                            </div>
                            <div class="col">
                                <input type="text" name="guideName" id="" class="form-control bg-light" placeholder="Enter Guide Name" value="<?php echo $_POST['guideName'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddGuide'])){
                                if($validation['guideNameStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Guide name is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                    
    
                                if($validation['duplicateNameStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Guide already exists!</small>
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
                            if(isset($_POST['btnAddGuide'])){
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
                            if(isset($_POST['btnAddGuide'])){
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
                                <p>Languages</p>
                            </div>
                            <div class="col">
                            <input type="text" name="languages" id="" class="form-control bg-light" placeholder="Enter Languages(e.g.English, ..)" value="<?php echo $_POST['languages'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddGuide'])){
                                if($validation['languagesStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Language is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="btnAddGuide" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Add</button>
                        </div>

                        <?php
                        if(isset($_POST['btnAddGuide'])){
                            if(!$validation['guideNameStatus'] && !$validation['emailStatus'] && !$validation['phoneStatus'] && !$validation['languagesStatus'] && !$validation['duplicateNameStatus']){
                                $guideName = $_POST['guideName'];
                                $email = $_POST['email'];
                                $phone = $_POST['phone'];
                                $languages = $_POST['languages'];
                            
                            
                                $insertGuide = "INSERT INTO tour_guides(GuideName,Email,Phone,Languages) VALUE(?,?,?,?)";
                                $insertRes = $connection -> prepare($insertGuide);
                                $insertRes -> execute([$guideName,$email,$phone,$languages]);
                            
                                echo "<script>
                                            Swal.fire({
                                                title: 'Done!',
                                                text: 'New tour guide is added successfully.',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = './TourGuide.php';
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
        <!-- Left Form End -->
        <!-- Right Table Start -->
        <div class="col d-flex justify-content-end">
            <div class="card mt-4 table-guide table-container">
                <div class="card-body">
                    <h5>Tour Guide List</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Languages</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $selectGuide = "SELECT * FROM tour_guides ORDER BY TourGuideID DESC";
                            $selectRes = $connection -> prepare($selectGuide);
                            $selectRes -> execute();
                            $guides = $selectRes->fetchAll(PDO::FETCH_ASSOC);

                            foreach($guides as $item){
                                $ID = $item['TourGuideID'];
                                $name = $item['GuideName'];
                                $guideEmail = $item['Email'];
                                $guidePhone = $item['Phone'];
                                $guideLanguages = $item['Languages'];
                                echo "
                                    <tr>
                                        <td>$name</td>
                                        <td>$guideEmail</td>
                                        <td>$guidePhone</td>
                                        <td>$guideLanguages</td>
                                        <td class='text-end'><a href='./DeleteGuide.php?guideID=$ID' class='btn btn-sm btn-danger'><i class='fa-solid fa-trash'></i></a></td>
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
        <!-- Right Table End -->
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>