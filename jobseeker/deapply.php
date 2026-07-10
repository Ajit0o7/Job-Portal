<?php 
session_start();
include '../database_configure.php';

$sid = isset($_SESSION['username']) ? $_SESSION['username'] : 0;
$aid = isset($_GET['a_id']) ? (int)$_GET['a_id'] : 0;

if($aid > 0 && $sid > 0) {
    // Only allow withdrawing your own application
    $insert = "UPDATE `application` SET `status`='none' WHERE application_id = '$aid' AND Seeker_id = '$sid'";
    mysqli_query($conn,$insert);
    ?> <script>alert('Application successfully withdrawn.');location.replace("<?php echo BASE_URL; ?>/jobseeker/joblist");</script><?php
} else {
    ?> <script>location.replace("<?php echo BASE_URL; ?>/jobseeker/joblist");</script><?php
}
?>