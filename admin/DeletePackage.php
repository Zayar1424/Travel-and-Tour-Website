<?php
require_once('../database/DbConnection.php');
session_start();

$id = $_REQUEST['packageID'];

// Change active status
$updatePackageActiveStatus = "UPDATE packages SET Active=? WHERE PackageID=?";
$packageUpdateRes = $connection -> prepare($updatePackageActiveStatus);
$packageUpdateRes -> execute([1,$id]);

// Store success message in session
$_SESSION['package_delete_success'] = "Deleted successfully.";

// Redirect to Destination.php
header("Location: Package.php");
exit();
?>