<?php
session_start();
include '../database_configure.php';

if (!isset($_SESSION['Adname'])) {
    header("Location: index.php");
    exit;
}

$s_id = isset($_REQUEST['s_id']) ? intval($_REQUEST['s_id']) : 0;
$delete = "DELETE FROM `employerlogin` WHERE employer_id = $s_id";
$query = mysqli_query($conn, $delete);

if($query){
    ?><script>alert('Employer deleted successfully.');location.replace('<?php echo BASE_URL; ?>/admin/viewemployerlist');</script><?php
} else {
    ?><script>alert('Error deleting employer.');location.replace('<?php echo BASE_URL; ?>/admin/viewemployerlist');</script><?php
}
?>