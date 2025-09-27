<?php
$title = "Edit Itinerary";
ob_start();
session_start();
require_once('../database/DbConnection.php');

$itineraryID = $_GET['itineraryID'] ?? $_POST['itineraryID'] ?? $_SESSION['itineraryID'] ?? null;

$selectItinerary = "SELECT * FROM itineraries WHERE ItineraryID=?";
$selectRes = $connection -> prepare($selectItinerary);
$selectRes -> execute([$itineraryID]);
$itineraryData = $selectRes -> fetch(PDO::FETCH_ASSOC);

$packageID = $itineraryData['PackageID'];
$name = $itineraryData['Name'];
$day = $itineraryData['Day'];
$activity = $itineraryData['Activity'];

if(isset($_POST['btnUpdateItinerary'])){
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
?>

<!-- Itinerary Form Start -->
<div class="container-fluid overflow-auto container-scroll">
    <div class="row mb-4">
        <div class="col-6">
            <div class="mt-3">
                <h4 class="ms-2">Edit Itinerary</h4>
            </div>
        
        
            <div class="card mt-2 shadow-sm input-form">
                <div class="card-body">
                    <form action="UpdateItinerary.php" method="POST">
                        <input type="hidden" name="itineraryID" value="<?php echo $itineraryID; ?>">
                        <input type="hidden" name="packageID" value="<?php echo $packageID; ?>">
                        <div class="row mt-2">
                            <div class="col-3">
                                <p>Day</p>
                            </div>
                            <div class="col">
                                <input type="number" name="day" id="" class="form-control bg-light" placeholder="Enter Day(e.g.1,2,..)" value="<?php echo $_POST['day'] ?? $day; ?>">
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdateItinerary'])){
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
                                <textarea type="text" name="itineraryName" id="" class="form-control bg-light" style="height: 70px; resize: none; overflow-y: auto;" placeholder="Enter Name" value=""><?php echo $_POST['itineraryName'] ?? $name; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdateItinerary'])){
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
                                <textarea type="text" name="activity" id="" class="form-control bg-light" style="height: 170px; resize: none; overflow-y: auto;" placeholder="Enter Activity(e.g.-time :activity 1 -time :activity 2 -time: activity 3)" value=""><?php echo $_POST['activity'] ?? $activity; ?></textarea>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['btnUpdateItinerary'])){
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
                            <button type="submit" name="btnUpdateItinerary" class="btn btn-primary btn-sm fs-6" style="width: 20%;">Save</button>
                        </div>

                        <?php
                            if(isset($_POST['btnUpdateItinerary'])){
                                if(!$itineraryValidation['dayStatus'] && !$itineraryValidation['itineraryNameStatus'] && !$itineraryValidation['activityStatus'] && $dayComplete){
                                    $itineraryID = $_POST['itineraryID'];
                                    $packageID = $_POST['packageID'];
                                    $itineraryName = $_POST['itineraryName'];
                                    $itineraryDay = $_POST['day'];
                                    $itineraryActivity = $_POST['activity'];

                                    $updateItinerary = "UPDATE itineraries SET PackageID=?,Name=?,Day=?,Activity=? WHERE ItineraryID=?";
                                    $updateRes = $connection -> prepare($updateItinerary);
                                    $updateRes -> execute([$packageID,$itineraryName,$itineraryDay,$itineraryActivity,$itineraryID]);

                                    $_SESSION['packageID'] = $packageID;
                                    $_SESSION['itineraryID'] = $itineraryID;
                                    
                                    echo "<script>
                                                Swal.fire({
                                                    title: 'Done!',
                                                    text: 'Itinerary is updated successfully.',
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
                                    $_SESSION['itineraryID'] = $itineraryID;
                                }
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Itinerary Form End -->
</div>
<?php
$content = ob_get_clean();
include('./layout/master.php');
?>