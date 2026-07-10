<?php
include '../database_configure.php';
$res = mysqli_query($conn, "SELECT sk_id, Seeker_id, image, pdffile FROM seekerresume WHERE Seeker_id = '23'");
while($r = mysqli_fetch_assoc($res)) {
    var_dump($r);
}
?>
