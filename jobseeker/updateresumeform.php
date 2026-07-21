<?php
session_start();
include '../database_configure.php';

if (!isset($_SESSION['username'])) {
    echo "<script type='text/javascript'>alert('Please sign in first'); location.replace('" . BASE_URL . "/jobseeker/jobseekerHome');</script>";
    exit;
}

$aid = intval($_SESSION['username']);
$jid = isset($_GET['sk_id']) ? intval($_GET['sk_id']) : 0;

if ($jid !== $aid) {
    echo "<script type='text/javascript'>alert('Unauthorized action'); location.replace('" . BASE_URL . "/jobseeker/jobseekerHome');</script>";
    exit;
}
    
    $month = date('m');
    $day = date('d');
    $year = date('Y');

    $today = $year . '-' . $month . '-' . $day;

    if($_POST){
    
        $Sname = mysqli_real_escape_string($conn, $_REQUEST['fullname']);
        $Semail = mysqli_real_escape_string($conn, $_REQUEST['email_s']);
        $Scontact = (int)$_REQUEST['contact_s'];

        $Scountry = mysqli_real_escape_string($conn, $_REQUEST['country']);
        $Sproviend  = mysqli_real_escape_string($conn, $_REQUEST['provience']);
        $district = mysqli_real_escape_string($conn, $_REQUEST['city']);
        $address = mysqli_real_escape_string($conn, $_REQUEST['address']);

        $education = mysqli_real_escape_string($conn, $_REQUEST['education']);
        $experience = mysqli_real_escape_string($conn, $_REQUEST['work']);
        $skill = mysqli_real_escape_string($conn, $_REQUEST['skill']);
        $description = mysqli_real_escape_string($conn, $_REQUEST['description']);

                $userfile = $_FILES['userfile'];

                $filename2 = $userfile['name'];
                $fileerror2 = $userfile['error'];
                $filetmp2 = $userfile['tmp_name'];

                $fileext2 = explode('.',$filename2);
                $filecheck2 = strtolower(end($fileext2));
                $fileextstored2 = array('pdf');

                // PDF upload will be handled below


                $user = $_SESSION['username'];
                
                $files = $_FILES['file'];
                $filename = $files['name'];
                $fileerror = $files['error'];
                $filetmp = $files['tmp_name'];

                $fileext = explode('.',$filename);
                $filecheck = strtolower(end($fileext));
                $fileextstored = array('png','jpg','jpeg','JPG');

                $image_update = "";
                if($filename != '' && in_array($filecheck,$fileextstored)){
                    $destinationfile = '../img/'.$filename;
                    move_uploaded_file($filetmp,$destinationfile);
                    $image_update = ", `image`='$destinationfile'";
                }

                $pdf_update = "";
                if($filename2 != '' && in_array($filecheck2,$fileextstored2)){
                    $destinationfile2 = '../img/'.$filename2;
                    move_uploaded_file($filetmp2,$destinationfile2);
                    $pdf_update = ", `pdffile`='$destinationfile2'";
                }

                $update = "UPDATE `seekerresume` SET `FullName`='$Sname',`EmailAddress`='$Semail',`Contact`='$Scontact',`Country`='$Scountry',`Provience`='$Sproviend',`City`='$district',`Address`='$address',`Education`='$education',`Workexp`='$experience',`skill`='$skill', `description`='$description' $pdf_update $image_update WHERE Seeker_id = '$jid'";

                $query = mysqli_query($conn,$update);
                if($query){ ?> <script>alert('Resume updated successfully');location.replace("<?php echo BASE_URL; ?>/jobseeker/resumepage");</script><?php }
    }

?>