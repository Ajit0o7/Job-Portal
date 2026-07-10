<?php
session_start();
include '../database_configure.php';

if (!isset($_SESSION['username'])) {
    echo "<script type='text/javascript'>alert('Signin before submitting form'); location.replace('" . BASE_URL . "/jobseeker/resumepage');</script>";
} else {
    if ($_POST) {
        $Sname = $_POST['fullname'];
        $Semail = $_POST['email_s'];
        $Scontact = (int)$_POST['contact_s'];

        $Scountry = $_POST['country'];
        $Sproviend = $_POST['provience'];
        $district = $_POST['city'];
        $address = $_POST['address'];

        $education = $_POST['education'];
        $experience = $_POST['work'];
        $skill = $_POST['skill'];

        // File handling for resume (PDF)
        $userfile = $_FILES['userfile'];
        $filename2 = $userfile['name'];
        $filetmp2 = $userfile['tmp_name'];
        $fileext2 = pathinfo($filename2, PATHINFO_EXTENSION);
        
        if (strtolower($fileext2) === 'pdf') {
            $destinationfile2 = '../img/' . $filename2;
            move_uploaded_file($filetmp2, $destinationfile2);
        } else {
            $destinationfile2 = '';
        }

        // File handling for profile picture
        $files = $_FILES['file'];
        $filename = $files['name'];
        $filetmp = $files['tmp_name'];
        $fileext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($fileext), ['png', 'jpg', 'jpeg'])) {
            $destinationfile = '../img/' . $filename;
            move_uploaded_file($filetmp, $destinationfile);
        } else {
            $destinationfile = '';
        }

        $user = $_SESSION['username'];

        // Insert data into the database
        $insert = "INSERT INTO `seekerresume`(`FullName`, `EmailAddress`, `Contact`, `Country`, `Provience`, `City`, `Address`, `pdffile`, `image`, `Seeker_id`, `Education`, `Workexp`, `skill`) 
                   VALUES ('$Sname','$Semail','$Scontact','$Scountry','$Sproviend','$district','$address','$destinationfile2','$destinationfile','$user','$education','$experience','$skill')";

        if (mysqli_query($conn, $insert)) {
            echo "<script>alert('Your CV has been Uploaded.'); location.replace('" . BASE_URL . "/jobseeker/resumepage');</script>";
        } else {
            echo "<script>alert('Error uploading CV. Please try again.');</script>";
        }
    }
}
?>