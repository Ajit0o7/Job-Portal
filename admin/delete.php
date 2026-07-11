<?php
session_start();
include '../database_configure.php';

if (!isset($_SESSION['Adname'])) {
    header("Location: index.php");
    exit;
}

$s_id = isset($_REQUEST['s_id']) ? intval($_REQUEST['s_id']) : 0;
$delete = "DELETE FROM `job_postings` WHERE job_id = $s_id";
$query = mysqli_query($conn, $delete);

if($query){
    ?><script>alert('Job list deleted successfully.');location.replace('dash');</script><?php
} else {
    ?><script>alert('Error deleting job list.');location.replace('dash');</script><?php
}
?>