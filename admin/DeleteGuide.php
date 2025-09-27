<?php
require_once('../database/DbConnection.php');
session_start();

$id = $_REQUEST['guideID'];

$deleteGuide = "DELETE FROM tour_guides WHERE TourGuideID=?";
$deleteRes = $connection -> prepare($deleteGuide);
$deleteRes -> execute([$id]);

// Store success message in session
$_SESSION['guide_delete_success'] = "Deleted successfully.";

// Redirect to Destination.php
header("Location: TourGuide.php");
exit();
?>