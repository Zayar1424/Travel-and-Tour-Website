<?php
require_once('../database/DbConnection.php');
session_start();

$id = $_REQUEST['destinationID'];

$selectDestination = "SELECT Image FROM destinations WHERE DestinationID=?";
$selectRes = $connection -> prepare($selectDestination);
$selectRes -> execute([$id]);
$data = $selectRes -> fetch(PDO::FETCH_ASSOC);
$imageName = $data['Image'];

if ($data && !empty($imageName) && file_exists("./../images/" . $imageName)) {
    unlink("./../images/" . $imageName);
}

$deleteDestination = "DELETE FROM destinations WHERE DestinationID=?";
$deleteRes = $connection -> prepare($deleteDestination);
$deleteRes -> execute([$id]);

// Store success message in session
$_SESSION['destination_delete_success'] = "Deleted successfully.";

// Redirect to Destination.php
header("Location: Destination.php");
exit();
?>