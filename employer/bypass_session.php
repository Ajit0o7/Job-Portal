<?php
session_start();
$_SESSION['username_e'] = 1; // Assuming employer ID 1 exists, or just to bypass check
header("Location: joblistform.php");
exit();
?>
