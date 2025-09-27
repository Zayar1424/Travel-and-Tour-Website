<?php
session_start();
session_destroy();
header("Location: ./../auth/Login.php");
exit();
?>