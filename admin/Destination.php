<?php
$title = "Destinations";
ob_start();
require_once('../database/DbConnection.php');

if(isset($_POST['btnAddDestination'])){
$validation = [
    'destinationStatus' => false,
    'duplicateDestinationStatus' => false,
    'imageStatus' => false,
];

$validation['destinationStatus'] = $_POST['destination'] == "" ? true:false;
$validation['imageStatus'] = $_FILES['image']['name'] == "" ? true:false;

$destination = $_POST['destination'];
    
$checkDestination = "SELECT COUNT(*) FROM destinations WHERE Destination=?";
$checkRes = $connection -> prepare($checkDestination);
$checkRes -> execute([$destination]);
$destinationCount = $checkRes -> fetchColumn();

$validation['duplicateDestinationStatus'] = $destinationCount>0 ? true:false;
}
?>

<div class="container-fluid overflow-auto container-scroll">
    <div class="row align-items-start mb-4">
        <!-- Left Form Start -->
        <div class="col-5">
            <div class="card mt-5 shadow-sm input-form">
                <div class="card-body">
                    <form action="Destination.php" method="POST" enctype="multipart/form-data">
                        <div class="row mt-2">
                            <div class="col-4">
                                <p>Destination</p>
                            </div>
                            <div class="col">
                                <input type="text" name="destination" id="" class="form-control bg-light" placeholder="Enter Destination Name" value="<?php echo $_POST['destination'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddDestination'])){
                                if($validation['destinationStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Destination name is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                    
    
                                if($validation['duplicateDestinationStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Destination already exists!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                    
                            }
                        ?>
                        
        
                        <div class="row mt-2">
                            <div class="col-4">
                                <p>Image</p>
                            </div>
                            <div class="col">
                                <input type="file" name="image" id="" class="form-control bg-light" onchange="loadFile(event, 'output')">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddDestination'])){
                                if($validation['imageStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Image is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                
                            }
                        ?>

                        <div class="row">
                            <div class="col-4">
                            </div>
                            <div class="col">
                                <img src="./../images/blank_img.png" id="output" class="w-100 img-thumbnail img-fluid output-img-small">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" name="btnAddDestination" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Add</button>
                        </div>

                        <?php
                        if(isset($_POST['btnAddDestination'])){
                            if(!$validation['destinationStatus'] && !$validation['imageStatus'] && !$validation['duplicateDestinationStatus']){
                                $destination = $_POST['destination'];
                            
                                $imageName = uniqid() . $_FILES['image']['name'];
                                $tmpName = $_FILES['image']['tmp_name'];
                                $targetFile = "./../images/".$imageName;
                                move_uploaded_file($tmpName,$targetFile);
                            
                                $insertDestination = "INSERT INTO destinations(Destination,Image) VALUE(?,?)";
                                $insertRes = $connection -> prepare($insertDestination);
                                $insertRes -> execute([$destination,$imageName]);
                            
                                echo "<script>
                                            Swal.fire({
                                                title: 'Done!',
                                                text: 'New destination is added successfully.',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = './Destination.php';
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
        <!-- Left Form Start -->
        <!-- Right Table Start -->
        <div class="col d-flex justify-content-end">
            <div class="card mt-4 table-destination table-container">
                <div class="card-body">
                    <h5>Destination List</h5>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Destination</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $selectDestination = "SELECT DestinationID,Destination FROM destinations ORDER BY DestinationID DESC";
                            $selectRes = $connection -> prepare($selectDestination);
                            $selectRes -> execute();
                            $destinations = $selectRes->fetchAll(PDO::FETCH_ASSOC);

                            foreach($destinations as $item){
                                $ID = $item['DestinationID'];
                                $name = $item['Destination'];
                                echo "
                                    <tr>
                                        <td>$name</td>
                                        <td class='text-end'><a href='./DeleteDestination.php?destinationID=$ID' class='btn btn-sm btn-danger'><i class='fa-solid fa-trash'></i></a></td>
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
        <!-- Right Table Start -->
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>