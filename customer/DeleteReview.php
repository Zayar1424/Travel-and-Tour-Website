<?php
require_once('../database/DbConnection.php');
session_start();

$id = $_REQUEST['reviewID'];

$deleteReview = "DELETE FROM reviews WHERE ReviewID=?";
$deleteRes = $connection -> prepare($deleteReview);
$deleteRes -> execute([$id]);

// Store success message in session
$_SESSION['review_delete_success'] = "Deleted successfully.";

// Redirect to Destination.php
header("Location: PackageDetail.php?packageID=".$_REQUEST['packageID']);
exit();
?>