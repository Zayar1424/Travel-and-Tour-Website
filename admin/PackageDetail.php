<?php
$title = "Package Detail";
ob_start();
session_start();
require_once('../database/DbConnection.php');

$packageID = $_GET['packageID'] ?? $_POST['packageID'] ?? $_SESSION['packageID'] ?? null;

// Package
$selectPackage = "SELECT p.*, d.Destination, d.DestinationID, t.GuideName, t.TourGuideID FROM packages p, destinations d, tour_guides t 
                WHERE p.DestinationID=d.DestinationID 
                AND p.TourGuideID=t.TourGuideID 
                AND PackageID=?";
$packageRes = $connection -> prepare($selectPackage);
$packageRes -> execute([$packageID]);
$packageData = $packageRes->fetch(PDO::FETCH_ASSOC);

$currentTitle = $packageData['Title'] ?? null;
$currentDestination = $packageData['DestinationID'] ?? null;
$currentDescription = $packageData['Description'] ?? null;
$currentDuration = $packageData['Duration'] ?? null;
$currentLanguages = $packageData['Languages'] ?? null;
$currentGuide = $packageData['TourGuideID'] ?? null;
$currentSize = $packageData['Size'] ?? null;
$currentPrice = $packageData['Price'] ?? null;
$currentHighlightOne = $packageData['Highlight1'] ?? null;
$currentHighlightTwo = $packageData['Highlight2'] ?? null;
$currentHighlightThree = $packageData['Highlight3'] ?? null;
$currentHighlightFour = $packageData['Highlight4'] ?? null;
$currentIncludedThings = $packageData['IncludedThings'] ?? null;
$currentExcludedThings = $packageData['ExcludedThings'] ?? null;
$currentInformation = $packageData['Info'] ?? null;
$currentImageOne = $packageData['Image1'] ?? null;
$currentImageTwo = $packageData['Image2'] ?? null;
$currentImageThree = $packageData['Image3'] ?? null;
$currentMap = $packageData['Map'] ?? null;



if(isset($_POST['btnUpdatePackage'])){
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
$validation['mapStatus'] = $_POST['map'] == "" ? true:false;

$title = $_POST['title'];
    
$checkTitle = "SELECT COUNT(*) FROM packages WHERE Title=? AND PackageID != ?";
$checkRes = $connection -> prepare($checkTitle);
$checkRes -> execute([$title, $packageID]);
$titleCount = $checkRes -> fetchColumn();

$validation['duplicateTitleStatus'] = $titleCount>0 ? true:false;

$validation['sizeCheck'] = $_POST['size'] < 2 ? true:false;
$validation['priceCheck'] = $_POST['price'] < 0 ? true:false;
}


if(isset($_POST['btnAddItinerary'])){
$itineraryValidation = [
    'dayStatus' => false,
    'dayCheck' =>false,
    'itineraryNameStatus' => false,
    'activityStatus' => false,
];

$dayComplete = false;

$itineraryValidation['dayStatus'] = $_POST['day'] == "" ? true:false;
$itineraryValidation['dayCheck'] = $_POST['day'] < 0 ? true:false;
$itineraryValidation['itineraryNameStatus'] = $_POST['itineraryName'] == "" ? true:false;
$itineraryValidation['activityStatus'] = $_POST['activity'] == "" ? true:false;
}

if(isset($_POST['btnAddAvailability'])){
$availabilityValidation = [
    'startDateStatus' => false,
    'endDateStatus' => false,
    'priceStatus' => false,
    'priceCheck' =>false,
];

$priceComplete = false;
    
$availabilityValidation['startDateStatus'] = $_POST['startDate'] == "" ? true:false;
$availabilityValidation['endDateStatus'] = $_POST['endDate'] == "" ? true:false;
$availabilityValidation['priceStatus'] = $_POST['availabilityPrice'] == "" ? true:false;
$availabilityValidation['priceCheck'] = $_POST['availabilityPrice'] < 0 ? true:false;
}
?>

<div class="container-fluid overflow-auto container-scroll">
    <!-- Package Form Start -->
    <div class="row mb-4">
        <div class="mt-3">
            <h4 class="ms-2">Package Detail</h4>
        </div>
        <div class="col-8">
            <div class="card mt-2 shadow-sm input-form">
                <div class="card-body">
                    <form action="PackageDetail.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="packageID" value="<?php echo $packageID; ?>">
                        <input type="hidden" name="currentImageOne" value="<?php echo $currentImageOne; ?>">
                        <input type="hidden" name="currentImageTwo" value="<?php echo $currentImageTwo; ?>">
                        <input type="hidden" name="currentImageThree" value="<?php echo $currentImageThree; ?>">
                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Title</p>
                            </div>
                            <div class="col">
                                <textarea name="title" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Title" value=""><?php echo $_POST['title'] ?? $currentTitle; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                            $selectedDestinationID = $_POST['destination'] ?? $currentDestination;
                                            $selectedDestination = ($selectedDestinationID == $destinationID) ? 'selected' : '';
                                            echo "<option value='$destinationID' $selectedDestination>$destinationName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="description" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Description" value=""><?php echo $_POST['description'] ?? $currentDescription; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <input type="text" name="duration" id="" class="form-control bg-light" placeholder="Enter Duration" value="<?php echo $_POST['duration'] ?? $currentDuration; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <input type="text" name="languages" id="" class="form-control bg-light" placeholder="Enter Languages(e.g.English, ..)" value="<?php echo $_POST['languages'] ?? $currentLanguages; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                            $selectedGuideID = $_POST['guide'] ?? $currentGuide;
                                            $selectedGuide = ($selectedGuideID == $guideID) ? 'selected' : '';
                                            echo "<option value='$guideID' $selectedGuide>$guideName</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <input type="number" name="size" id="" class="form-control bg-light" placeholder="Enter Size(e.g.10,20,..)" value="<?php echo $_POST['size'] ?? $currentSize; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <input type="number" name="price" class="form-control" placeholder="Enter Price" aria-label="Username" aria-describedby="addon-wrapping" value="<?php echo $_POST['price'] ?? $currentPrice; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="highlightOne" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightOne'] ?? $currentHighlightOne; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="highlightTwo" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightTwo'] ?? $currentHighlightTwo; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="highlightThree" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightThree'] ?? $currentHighlightThree; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="highlightFour" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Highlight" value=""><?php echo $_POST['highlightFour'] ?? $currentHighlightFour; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="includedThings" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Included Things(e.g.-include 1 -include 2 -include 3)" value=""><?php echo $_POST['includedThings'] ?? $currentIncludedThings; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="excludedThings" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Excluded Things(e.g.-exclude 1 -exclude 2 -exclude 3)" value=""><?php echo $_POST['excludedThings'] ?? $currentExcludedThings; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                                <textarea name="information" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Information(e.g.-info 1 -info 2 -info 3)" value=""><?php echo $_POST['information'] ?? $currentInformation; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                       
                        <div class="row">
                            <div class="col-3">
                            </div>
                            <div class="col">
                                <img src="./../images/<?php echo $currentImageOne ? $currentImageOne : './../images/blank_img.png'; ?>" id="output1" class="w-100 img-thumbnail img-fluid output-img">
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
                        
                        <div class="row">
                            <div class="col-3">
                            </div>
                            <div class="col">
                                <img src="./../images/<?php echo $currentImageTwo ? $currentImageTwo : './../images/blank_img.png'; ?>" id="output2" class="w-100 img-thumbnail img-fluid output-img">
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
                        
                        <div class="row">
                            <div class="col-3">
                            </div>
                            <div class="col">
                                <img src="./../images/<?php echo $currentImageThree ? $currentImageThree : './../images/blank_img.png'; ?>" id="output3" class="w-100 img-thumbnail img-fluid output-img">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Map URL</p>
                            </div>
                            <div class="col">
                                <textarea name="map" id="" class="form-control bg-light" style="height: 120px; resize: none; overflow-y: auto;" placeholder="Enter Map URL" value=""><?php echo $_POST['map'] ?? $currentMap; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdatePackage'])){
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
                            <button type="submit" name="btnUpdatePackage" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Update</button>
                        </div>

                        <?php
                        if(isset($_POST['btnUpdatePackage'])){
                            if(!in_array(true, $validation, true)){
                                if($completeValidation['sizeCompleteStatus'] && $completeValidation['priceCompleteStatus']){
                                    $packageID = $_POST['packageID'];
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
                                    $imageOne = $packageData['Image1'] ?? null;
                                    $imageTwo = $packageData['Image2'] ?? null;
                                    $imageThree = $packageData['Image3'] ?? null;
                                    $map = $_POST['map'];
                                    $currentImageOne = $_POST['currentImageOne'];
                                    $currentImageTwo = $_POST['currentImageTwo'];
                                    $currentImageThree = $_POST['currentImageThree'];

                                    $imageFields = [
                                        'imageOne' => $currentImageOne ?? '',
                                        'imageTwo' => $currentImageTwo ?? '',
                                        'imageThree' => $currentImageThree ?? ''
                                    ];
                                    
                                    foreach ($imageFields as $fieldName => $currentImage) {
                                        if (!empty($_FILES[$fieldName]['name'])) {
                                            // Delete old image
                                            if (!empty($currentImage) && file_exists("./../images/" . $currentImage)) {
                                                unlink("./../images/" . $currentImage);
                                            }
                                    
                                            $newImageName = uniqid() . $_FILES[$fieldName]['name'];
                                            $tmpName = $_FILES[$fieldName]['tmp_name'];
                                            $targetPath = "./../images/" . $newImageName;
                                            move_uploaded_file($tmpName, $targetPath);
                                    
                                            // Assign new name to appropriate variable
                                            if ($fieldName === 'imageOne') {
                                                $imageOne = $newImageName;
                                            } elseif ($fieldName === 'imageTwo') {
                                                $imageTwo = $newImageName;
                                            } elseif ($fieldName === 'imageThree') {
                                                $imageThree = $newImageName;
                                            }
                                        }
                                    }
                                    

                                    $updatePackage = "UPDATE packages SET DestinationID=?,TourGuideID=?,Title=?,Description=?,Duration=?,Languages=?,Size=?,
                                                    Price=?,Highlight1=?,Highlight2=?,Highlight3=?,Highlight4=?,IncludedThings=?,ExcludedThings=?,
                                                    Info=?,Image1=?,Image2=?,Image3=?,Map=? WHERE PackageID=?";
                                    $updatePackageRes = $connection -> prepare($updatePackage);
                                    $updatePackageRes -> execute([$destination,$guide,$title,$description,$duration,$languages,$size,$price,$highlightOne,$highlightTwo,$highlightThree,$highlightFour,$includedThings,$excludedThings,$information,$imageOne,$imageTwo,$imageThree,$map,$packageID]);
                                    
                                    $_SESSION['packageID'] = $packageID;
                                    
                                    echo "<script>
                                                Swal.fire({
                                                    title: 'Done!',
                                                    text: 'Package is updated successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = './PackageDetail.php?packageID=" . $packageID . "';
                                                    }
                                                });
                                            </script>";
                                }
                                
                            }
                            else{
                                $_SESSION['packageID'] = $_POST['packageID'];
                            }
                        }
                        ?>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Package Form End -->
    <!-- Itinerary Form Start -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="mt-3">
                <h4 class="ms-2">Itineraries</h4>
            </div>
        
        
            <div class="card mt-2 shadow-sm input-form">
                <div class="card-body">
                    <form action="PackageDetail.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="packageID" value="<?php echo $packageID; ?>">
                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Day</p>
                            </div>
                            <div class="col">
                                <input type="number" name="day" id="" class="form-control bg-light" placeholder="Enter Day(e.g.1,2,..)" value="<?php echo $_POST['day'] ?? ""; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddItinerary'])){
                                if($itineraryValidation['dayStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Day is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else if($itineraryValidation['dayCheck']){
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
                                    $dayComplete = true;
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Name</p>
                            </div>
                            <div class="col">
                                <textarea type="text" name="itineraryName" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Name" value=""><?php echo $_POST['itineraryName'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddItinerary'])){
                                if($itineraryValidation['itineraryNameStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Name is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Activity</p>
                            </div>
                            <div class="col">
                                <textarea type="text" name="activity" id="" class="form-control bg-light" style="height: 170px; resize: none; overflow-y: auto;" placeholder="Enter Activity(e.g.-time :activity 1 -time :activity 2 -time: activity 3)" value=""><?php echo $_POST['activity'] ?? ""; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddItinerary'])){
                                if($itineraryValidation['activityStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Activity is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="btnAddItinerary" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Add</button>
                        </div>

                        <?php
                            if(isset($_POST['btnAddItinerary'])){
                                if(!$itineraryValidation['dayStatus'] && !$itineraryValidation['itineraryNameStatus'] && !$itineraryValidation['activityStatus'] && $dayComplete){
                                    $packageID = $_POST['packageID'];
                                    $itineraryName = $_POST['itineraryName'];
                                    $day = $_POST['day'];
                                    $activity = $_POST['activity'];

                                    $insertItinerary = "INSERT INTO itineraries(PackageID,Name,Day,Activity) VALUE(?,?,?,?)";
                                    $itineraryInsertRes = $connection -> prepare($insertItinerary);
                                    $itineraryInsertRes -> execute([$packageID,$itineraryName,$day,$activity]);

                                    $_SESSION['packageID'] = $packageID;
                                    
                                    echo "<script>
                                                Swal.fire({
                                                    title: 'Done!',
                                                    text: 'New itinerary is added successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = './PackageDetail.php?packageID=" . $packageID . "';
                                                    }
                                                });
                                            </script>";
                                }
                                else{
                                    $_SESSION['packageID'] = $_POST['packageID'];
                                }
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Itinerary Form End -->
    <!-- Itinerary Table Start -->
    <div class="row mb-4">
        <div class="col">
                <div class="card mt-4 table-itinerary table-container">
                    <div class="card-body">
                        <h5>Itinerary List</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Name</th>
                                    <th>Activity</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $selectItinerary = "SELECT * FROM itineraries WHERE PackageID=? ORDER BY Day ASC";
                                $itinerarySelectRes = $connection -> prepare($selectItinerary);
                                $itinerarySelectRes -> execute([$packageID]);
                                $itineraries = $itinerarySelectRes->fetchAll(PDO::FETCH_ASSOC);

                                foreach($itineraries as $itinerary){
                                    $itineraryId = $itinerary['ItineraryID'];
                                    $day = $itinerary['Day'];
                                    $name = $itinerary['Name'];
                                    $activity = $itinerary['Activity'];
                                
                                    echo "
                                        <tr>
                                            <td>Day $day</td>
                                            <td>$name</td>
                                            <td>$activity</td>
                                            <td>
                                                <a href='./UpdateItinerary.php?itineraryID=$itineraryId' class='btn btn-sm btn-primary'><i class='fa-solid fa-pen-to-square'></i></a>
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
    <!-- Itinerary Table End -->
    <!-- Availability Form Start -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="mt-3">
                <h4 class="ms-2">Availability</h4>
            </div>
        
        
            <div class="card mt-2 shadow-sm input-form">
                <div class="card-body">
                    <form action="PackageDetail.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="packageID" value="<?php echo $packageID; ?>">
                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Start Date</p>
                            </div>
                            <div class="col">
                                <input type="text" id="" name="startDate" class="form-control bg-light datepicker">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddAvailability'])){
                                if($availabilityValidation['startDateStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Start date is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>End Date</p>
                            </div>
                            <div class="col">
                                <input type="text" id="" name="endDate" class="form-control bg-light datepicker">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddAvailability'])){
                                if($availabilityValidation['endDateStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">End date is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                            }
                        ?>

                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Price</p>
                            </div>
                            <div class="col input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">฿</span>
                                <input type="number" name="availabilityPrice" id="" class="form-control bg-light">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnAddAvailability'])){
                                if($availabilityValidation['priceStatus']){
                                    echo '
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col">
                                            <small class="text-danger ms-2">Price is required!</small>
                                        </div>
                                    </div>
                                    ';
                                }
                                else if($availabilityValidation['priceCheck']){
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
                                    $priceComplete = true;
                                }
                            }
                        ?>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" name="btnAddAvailability" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Add</button>
                        </div>

                        <?php
                            if(isset($_POST['btnAddAvailability'])){
                                if(!$availabilityValidation['startDateStatus'] && !$availabilityValidation['endDateStatus'] && !$availabilityValidation['priceStatus'] && !$availabilityValidation['priceCheck'] && $priceComplete){
                                    $packageID = $_POST['packageID'];
                                    $startDate = $_POST['startDate'];
                                    $endDate = $_POST['endDate'];
                                    $availabilityPrice = $_POST['availabilityPrice'];

                                    $insertAvailability = "INSERT INTO availability(PackageID,StartDate,EndDate,Price) VALUE(?,?,?,?)";
                                    $availabilityInsertRes = $connection -> prepare($insertAvailability);
                                    $availabilityInsertRes -> execute([$packageID,$startDate,$endDate,$availabilityPrice]);

                                    $_SESSION['packageID'] = $packageID;
                                    
                                    echo "<script>
                                                Swal.fire({
                                                    title: 'Done!',
                                                    text: 'New availability is added successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = './PackageDetail.php?packageID=" . $packageID . "';
                                                    }
                                                });
                                            </script>";
                                }
                                else{
                                    $_SESSION['packageID'] = $_POST['packageID'];
                                }
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Availability Form End -->
    <!-- Availability Table Start -->
    <div class="row mb-4">
        <div class="col">
                <div class="card mt-4 table-availability table-container">
                    <div class="card-body">
                        <h5>Availability List</h5>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $selectAvailability = "SELECT * FROM availability WHERE PackageID=? ORDER BY StartDate DESC";
                                $availabilitySelectRes = $connection -> prepare($selectAvailability);
                                $availabilitySelectRes -> execute([$packageID]);
                                $availabilityData = $availabilitySelectRes->fetchAll(PDO::FETCH_ASSOC);

                                foreach($availabilityData as $availability){
                                    $availabilityId = $availability['AvailabilityID'];
                                    $startDate = $availability['StartDate'];
                                    $endDate = $availability['EndDate'];
                                    $availabilityPrice = $availability['Price'];
                                
                                    echo "
                                        <tr>
                                            <td>$startDate</td>
                                            <td>$endDate</td>
                                            <td>฿$availabilityPrice</td>
                                            <td>
                                                <a href='./UpdateAvailability.php?availabilityID=$availabilityId' class='btn btn-sm btn-primary'><i class='fa-solid fa-pen-to-square'></i></a>
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
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>