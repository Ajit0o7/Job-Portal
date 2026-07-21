<?php
include 'database_configure.php';
$res = $conn->query("DESCRIBE employerlogin");
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
