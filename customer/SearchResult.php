<?php
$title = "Packages";
ob_start();
require_once('../database/DbConnection.php');

$noResult = false;
// Get the search query from the URL
if (isset($_GET['searchKey']) && !empty($_GET['searchKey'])) {
    $sort = isset($_GET['sort']) ? $_GET['sort'] : "latest";

    // Define sorting conditions
    $orderBy = "p.CreatedAt DESC";
    if ($sort == "price_asc") {
        $orderBy = "p.Price ASC";
    } elseif ($sort == "price_desc") {
        $orderBy = "p.Price DESC";
    }
    $searchKey = $_GET['searchKey'];
    $searchQuery = "%" . $searchKey . "%";

    // SQL query to find destinations matching the search
    $selectPackages = "SELECT p.*,d.Destination FROM packages p, destinations d 
                        WHERE p.DestinationID=d.DestinationID
                        AND Destination LIKE ?
                        AND p.Active =?
                        ORDER BY $orderBy";
    $packageSelectRes = $connection->prepare($selectPackages);
    $packageSelectRes->execute([$searchQuery,0]);
    $packages = $packageSelectRes->fetchAll(PDO::FETCH_ASSOC);
    $packageCount = count($packages);

    if($packageCount==0){
        $noResult = true;
    }
} else {
    
    header("Location: Home.php");
    exit();
}
?>

<div class="container-fluid px-0">
    
    <h4 class="mt-4 mx-5">"<?php echo $searchKey?>"</h4>
    <div class="mx-5 row">
        <div class="col ms-0">
        <?php
        if($packageCount == 1){
            echo '<p>'. $packageCount .' package found</p>';
        }
        if($packageCount > 1){
            echo '<p>'. $packageCount .' packages found</p>';
        }
        ?>
        </div>
        <div class="col d-flex justify-content-end">
            <div class="row">
                <div class="col">
                    <p class="mt-2">Sort by:</p>
                </div>
                <div class="col">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                            if(!empty($_GET['sort'])){
                                if($_GET['sort'] == "latest"){
                                    echo "Latest";
                                }
                                elseif($_GET['sort'] == "price_asc"){
                                    echo "Price - low to high";
                                }
                                elseif($_GET['sort'] == "price_desc"){
                                    echo "Price - high to low";
                                }
                            }else{
                                echo "Latest";
                            }
                        ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item sort-option" href="#" data-sort="latest">Latest</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="price_asc">Price - low to high</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="price_desc">Price - high to low</a></li>
                    </ul>
                </div>

                </div>
            </div>
            
        </div>
    </div>
        <?php
        if($noResult){
            echo '
            <p class="mt-4 mx-5">No package found.</p>
            ';
        }
        ?>
    
    <div class="px-5 mt-1">
        <div class="row">
            <?php
            foreach ($packages as $package) {
                $packageID = $package['PackageID'];
                echo '
                    <div class="col-12 col-sm-6 col-lg-3 mt-3 package-card-1">
                        <a href="./PackageDetail.php?packageID='.$packageID.'">
                        <div class="card">
                            <div class="image-container"><img src="./../images/'.$package['Image1'] .'" class=" w-100"></div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">'.$package['Title'].'</h5>
                                <div class="row mt-2">
                                            <div class="col-1"><i class="fa-regular fa-clock"></i></div>
                                            <div class="col ms-2"><p class="card-text text-secondary">'.$package['Duration'].'</p></div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-1"><i class="fa-solid fa-people-group"></i></div>
                                            <div class="col ms-2"><p class="card-text text-secondary">'.$package['Size'].' people tour</p></div>
                                        </div>
                                <div class="mt-auto">
                                    <p class="card-text">฿'.$package['Price'].' per person</p>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                ';
            }
            ?>
        </div>
        <!-- Pagination -->
        <ul class="pagination1 pagination justify-content-start mt-4">
                    
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include('./layout/master.php');
?>