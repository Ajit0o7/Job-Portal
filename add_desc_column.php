<?php
include 'database_configure.php';
$sql = "ALTER TABLE seekerresume ADD COLUMN description TEXT NULL";
if ($conn->query($sql) === TRUE) {
    echo "Column description added successfully";
} else {
    echo "Error adding column: " . $conn->error;
}
?>
