<?php
$title = "Packages";
ob_start();
session_start();
require_once('../database/DbConnection.php');

if(isset($_POST['btnAddPackage'])){
$validation = [
    'titleStatus' => false,
    'duplicateTitleStatus' => false,
    'destinationStatus' => false,
    'descriptionStatus' => false,
    'durationStatus' => false,
    'languagesStatus' => false,
    'guideStatus' => false,
    'sizeStatus' => false,
    'sizeCheck' => false,
    
    'priceStatus' => false,
    'priceCheck' => false,
    
    'highlightOneStatus' => false,
    'highlightTwoStatus' => false,
    'highlightThreeStatus' => false,
    'highlightFourStatus' => false,
    'includedThingsStatus' => false,
    'excludedThingsStatus' => false,
    'informationStatus' => false,
    'imageOneStatus' => false,
    'imageTwoStatus' => false,
    'imageThreeStatus' => false,
    'mapStatus' => false,
];

$completeValidation = [
    'sizeCompleteStatus' => false,
    'priceCompleteStatus' => false,
];

$validation['titleStatus'] = $_POST['title'] == "" ? true:false;
$validation['destinationStatus'] = $_POST['destination'] == "" ? true:false;
$validation['descriptionStatus'] = $_POST['description'] == "" ? true:false;
$validation['durationStatus'] = $_POST['duration'] == "" ? true:false;
$validation['languagesStatus'] = $_POST['languages'] == "" ? true:false;
$validation['guideStatus'] = $_POST['guide'] == "" ? true:false;
$validation['sizeStatus'] = $_POST['size'] == "" ? true:false;
$validation['priceStatus'] = $_POST['price'] == "" ? true:false;
$validation['highlightOneStatus'] = $_POST['highlightOne'] == "" ? true:false;
$validation['highlightTwoStatus'] = $_POST['highlightTwo'] == "" ? true:false;
$validation['highlightThreeStatus'] = $_POST['highlightThree'] == "" ? true:false;
$validation['highlightFourStatus'] = $_POST['highlightFour'] == "" ? true:false;
$validation['includedThingsStatus'] = $_POST['includedThings'] == "" ? true:false;
$validation['excludedThingsStatus'] = $_POST['excludedThings'] == "" ? true:false;
$validation['informationStatus'] = $_POST['information'] == "" ? true:false;
$validation['imageOneStatus'] = $_FILES['imageOne']['name'] == "" ? true:false;
$validation['imageTwoStatus'] = $_FILES['imageTwo']['name'] == "" ? true:false;
$validation['imageThreeStatus'] = $_FILES['imageThree']['name'] == "" ? true:false;
$validation['mapStatus'] = $_POST['map'] == "" ? true:false;

$title = $_POST['title'];
    
$checkTitle = "SELECT COUNT(*) FROM packages WHERE Title=?";
$checkRes = $connection -> prepare($checkTitle);
$checkRes -> execute([$title]);
$titleCount = $checkRes -> fetchColumn();

$validation['duplicateTitleStatus'] = $titleCount>0 ? true:false;

$validation['sizeCheck'] = $_POST['size'] < 2 ? true:false;
$validation['priceCheck'] = $_POST['price'] < 0 ? true:false;
}
?>

<div class="container-fluid overflow-auto container-scroll">
    <!-- Package Form Start -->
    <div class="row mb-4">
        <div class="col-8">
            <div class="card mt-4 shadow-sm input-form">
                <div class="card-body">
                    <form action="Package.php" method="POST" enctype="multipart/form-data">
                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Title</p>
                            </div>
                            <div class="col">
                                <textarea name="title" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Title" value=""><?php echo $_POST['title'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['titleStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Title is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                    
    
                                if($validation['duplicateTitleStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Title already exists!</small>
                                        </div>
                                    </div>
                                    ';
                                }

                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Destination</p>
                            </div>
                            <div class="col">
                                <select name="destination" class="form-control form-select bg-light" id="">
                                    <option value="">Choose Destination</option>
                                    <?php
                                        $selectDestination = "SELECT DestinationID, Destination FROM destinations";
                                        $destinationRes = $connection -> prepare($selectDestination);
                                        $destinationRes -> execute();
                                        $destinations = $destinationRes -> fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($destinations as $destination) {
                                            $destinationID = $destination['DestinationID'];
                                            $destinationName = $destination['Destination'];
                                            $selectedDestinationID = $_POST['destination'] ?? '';
                                            $selectedDestination = ($selectedDestinationID == $destinationID) ? 'selected' : '';
                                            echo "<option value='$destinationID' $selectedDestination>$destinationName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['destinationStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Destination is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Description</p>
                            </div>
                            <div class="col">
                                <textarea name="description" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Description" value=""><?php echo $_POST['description'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['descriptionStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Description is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Duration</p>
                            </div>
                            <div class="col">
                                <input type="text" name="duration" id="" class="form-control bg-light" placeholder="Enter Duration" value="<?php echo $_POST['duration'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['durationStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Duration is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Languages</p>
                            </div>
                            <div class="col">
                                <input type="text" name="languages" id="" class="form-control bg-light" placeholder="Enter Languages(e.g.English, ..)" value="<?php echo $_POST['languages'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['languagesStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Language is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Tour Guide</p>
                            </div>
                            <div class="col">
                                <select name="guide" class="form-control form-select bg-light" id="">
                                    <option value="">Choose Tour Guide</option>
                                    <?php
                                        $selectGuide = "SELECT TourGuideID, GuideName FROM tour_guides";
                                        $guideRes = $connection -> prepare($selectGuide);
                                        $guideRes -> execute();
                                        $guides = $guideRes -> fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($guides as $guide) {
                                            $guideID = $guide['TourGuideID'];
                                            $guideName = $guide['GuideName'];
                                            $selectedGuideID = $_POST['guide'] ?? '';
                                            $selectedGuide = ($selectedGuideID == $guideID) ? 'selected' : '';
                                            echo "<option value='$guideID' $selectedGuide>$guideName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['guideStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Tour Guide is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Size</p>
                            </div>
                            <div class="col">
                                <input type="number" name="size" id="" class="form-control bg-light" placeholder="Enter Size(e.g.10,20,..)" value="<?php echo $_POST['size'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['sizeStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Size is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else if($validation['sizeCheck']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Size must be at least 2!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else{
                                    $completeValidation['sizeCompleteStatus'] = true;
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Price</p>
                            </div>
                            <div class="col input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">฿</span>
                                <input type="number" name="price" class="form-control" placeholder="Enter Price" aria-label="Username" aria-describedby="addon-wrapping" value="<?php echo $_POST['price'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['priceStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Price is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else if($validation['priceCheck']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Please enter a valid number!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else{
                                    $completeValidation['priceCompleteStatus'] = true;
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Highlight 1</p>
                            </div>
                            <div class="col">
                                <textarea name="highlightOne" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightOne'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['highlightOneStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Hightlight 1 is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Highlight 2</p>
                            </div>
                            <div class="col">
                                <textarea name="highlightTwo" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightTwo'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['highlightTwoStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Highlight 2 is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Highlight 3</p>
                            </div>
                            <div class="col">
                                <textarea name="highlightThree" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightThree'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['highlightThreeStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Highlight 3 is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Highlight 4</p>
                            </div>
                            <div class="col">
                                <textarea name="highlightFour" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightFour'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['highlightFourStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Highlight 4 is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Includes</p>
                            </div>
                            <div class="col">
                                <textarea name="includedThings" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Included Things(e.g.-include 1 -include 2 -include 3) " value=""><?php echo $_POST['includedThings'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['includedThingsStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Includes are required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Excludes</p>
                            </div>
                            <div class="col">
                                <textarea name="excludedThings" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Excluded Things(e.g.-exclude 1 -exclude 2 -exclude 3)" value=""><?php echo $_POST['excludedThings'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['excludedThingsStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Excludes are required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Infomation</p>
                            </div>
                            <div class="col">
                                <textarea name="information" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Information(e.g.-info 1 -info 2 -info 3)" value=""><?php echo $_POST['information'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['informationStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Information is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Image 1</p>
                            </div>
                            <div class="col">
                                <input type="file" name="imageOne" id="" class="form-control bg-light" onchange="loadFile(event, 'output1')">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['imageOneStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Image 1 is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>
                        <div class="row">
                            <div class="col-3">
                            </div>
                            <div class="col">
                                <img src="./../images/blank_img.png" id="output1" class="w-100 img-thumbnail img-fluid output-img">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Image 2</p>
                            </div>
                            <div class="col">
                                <input type="file" name="imageTwo" id="" class="form-control bg-light" onchange="loadFile(event, 'output2')">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['imageTwoStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Image 2 is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>
                        <div class="row">
                            <div class="col-3">
                            </div>
                            <div class="col">
                                <img src="./../images/blank_img.png" id="output2" class="w-100 img-thumbnail img-fluid output-img">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Image 3</p>
                            </div>
                            <div class="col">
                                <input type="file" name="imageThree" id="" class="form-control bg-light" onchange="loadFile(event, 'output3')">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['imageThreeStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Image 3 is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>
                        <div class="row">
                            <div class="col-3">
                            </div>
                            <div class="col">
                                <img src="./../images/blank_img.png" id="output3" class="w-100 img-thumbnail img-fluid output-img">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Map URL</p>
                            </div>
                            <div class="col">
                                <textarea name="map" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Map URL" value=""><?php echo $_POST['map'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddPackage'])){
                                if($validation['mapStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Map URL is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="btnAddPackage" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Add</button>
                        </div>

                        <?php
                        if(isset($_POST['btnAddPackage'])){
                            if(!in_array(true, $validation, true)){
                                if($completeValidation['sizeCompleteStatus'] && $completeValidation['priceCompleteStatus']){
                                    $title = $_POST['title'];
                                    $destination = $_POST['destination'];
                                    $description = $_POST['description'];
                                    $duration = $_POST['duration'];
                                    $languages = $_POST['languages'];
                                    $guide = $_POST['guide'];
                                    $size = $_POST['size'];
                                    $price = $_POST['price'];
                                    $highlightOne = $_POST['highlightOne'];
                                    $highlightTwo = $_POST['highlightTwo'];
                                    $highlightThree = $_POST['highlightThree'];
                                    $highlightFour = $_POST['highlightFour'];
                                    $includedThings = $_POST['includedThings'];
                                    $excludedThings = $_POST['excludedThings'];
                                    $information = $_POST['information'];

                                    $imageOne = uniqid() . $_FILES['imageOne']['name'];
                                    $tmpNameOne = $_FILES['imageOne']['tmp_name'];
                                    $targetFileOne = "./../images/".$imageOne;
                                    move_uploaded_file($tmpNameOne,$targetFileOne);

                                    $imageTwo = uniqid() . $_FILES['imageTwo']['name'];
                                    $tmpNameTwo = $_FILES['imageTwo']['tmp_name'];
                                    $targetFileTwo = "./../images/".$imageTwo;
                                    move_uploaded_file($tmpNameTwo,$targetFileTwo);

                                    $imageThree = uniqid() . $_FILES['imageThree']['name'];
                                    $tmpNameThree = $_FILES['imageThree']['tmp_name'];
                                    $targetFileThree = "./../images/".$imageThree;
                                    move_uploaded_file($tmpNameThree,$targetFileThree);

                                    $map = $_POST['map'];

                                    $insertPackage = "INSERT INTO packages(DestinationID,TourGuideID,Title,Description,Duration,Languages,Size,
                                                        Price,Highlight1,Highlight2,Highlight3,Highlight4,IncludedThings,ExcludedThings,
                                                        Info,Image1,Image2,Image3,Map) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                    $insertRes = $connection -> prepare($insertPackage);
                                    $insertRes -> execute([$destination,$guide,$title,$description,$duration,$languages,$size,$price,$highlightOne,$highlightTwo,$highlightThree,$highlightFour,$includedThings,$excludedThings,$information,$imageOne,$imageTwo,$imageThree,$map]);

                                    echo "<script>
                                            Swal.fire({
                                                title: 'Done!',
                                                text: 'New package is added successfully.',
                                                icon: 'success',
                                                confirmButtonText: 'OK'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = './Package.php';
                                                }
                                            });
                                        </script>";
                                }
                                
                            }
                        }
                        ?>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Package Form End -->
    <!-- Package Table Start -->
    <div class="row mb-4">
        <div class="col">
                <div class="card mt-4 table-package table-container">
                    <div class="card-body">
                        <h5>Package List</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Destination</th>
                                    <th>Duration</th>
                                    <th>Languages</th>
                                    <th>Tour Guide</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $selectPackage = "SELECT p.PackageID, p.Title, d.Destination, p.Duration, p.Languages, t.GuideName, p.Size, p.Price FROM packages p, destinations d, tour_guides t 
                                                WHERE p.DestinationID=d.DestinationID 
                                                AND p.TourGuideID=t.TourGuideID
                                                AND p.Active=?
                                                ORDER BY p.PackageID DESC";
                                $selectRes = $connection -> prepare($selectPackage);
                                $selectRes -> execute([0]);
                                $packages = $selectRes->fetchAll(PDO::FETCH_ASSOC);

                                foreach($packages as $item){
                                    $ID = $item['PackageID'];
                                    $packageTitle = $item['Title'];
                                    $packageDestination = $item['Destination'];
                                    $packageDuration = $item['Duration'];
                                    $packageLanguages = $item['Languages'];
                                    $packageGuide = $item['GuideName'];
                                    $packageSize = $item['Size'];
                                    $packagePrice = $item['Price'];
                                    echo "
                                        <tr>
                                            <td class='w-25'>$packageTitle</td>
                                            <td>$packageDestination</td>
                                            <td>$packageDuration</td>
                                            <td>$packageLanguages</td>
                                            <td>$packageGuide</td>
                                            <td>$packageSize</td>
                                            <td>฿$packagePrice</td>
                                            <td>
                                            <a href='./PackageDetail.php?packageID=$ID' class='btn btn-sm btn-primary'><i class='fa-solid fa-circle-info'></i></a>
                                            <a href='./DeletePackage.php?packageID=$ID' class='btn btn-sm btn-danger'><i class='fa-solid fa-trash'></i></a>
                                            </td>
                                        </tr>";
                                           
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
    <!-- Package Table End -->
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>