<?php
require_once('../database/DbConnection.php');
session_start();

$id = $_REQUEST['adminID'];

$selectAdmin = "SELECT ProfileImage FROM users WHERE UserID=?";
$selectRes = $connection -> prepare($selectAdmin);
$selectRes -> execute([$id]);
$data = $selectRes -> fetch(PDO::FETCH_ASSOC);
$imageName = $data['ProfileImage'];

if ($data && !empty($imageName) && file_exists("./../images/" . $imageName)) {
    unlink("./../images/" . $imageName);
}

$deleteAdmin = "DELETE FROM users WHERE UserID=?";
$deleteRes = $connection -> prepare($deleteAdmin);
$deleteRes -> execute([$id]);

// Store success message in session
$_SESSION['admin_delete_success'] = "Deleted successfully.";

// Redirect to Destination.php
header("Location: ManageAdmin.php");
exit();
?>