<?php
require_once('../database/DbConnection.php');
session_start();

$id = $_REQUEST['paymentTypeID'];

$deleteType = "DELETE FROM payment_types WHERE PaymentTypeID=?";
$deleteRes = $connection -> prepare($deleteType);
$deleteRes -> execute([$id]);

// Store success message in session
$_SESSION['type_delete_success'] = "Deleted successfully.";

// Redirect to Destination.php
header("Location: PaymentType.php");
exit();
?>