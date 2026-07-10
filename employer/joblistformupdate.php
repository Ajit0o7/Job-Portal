<?php
session_start();
include '../database_configure.php';

$id = $_SESSION['username'];

$jid = $_GET['j_id'];
    
    $month = date('m');
    $day = date('d');
    $year = date('Y');

    $today = $year . '-' . $month . '-' . $day;

    if($_POST){
    
        $title = $_REQUEST['title'];
        $location = $_REQUEST['location'];
        $Salary = $_REQUEST['salary'];
        $requirement = $_REQUEST['requirements'];
        $description = $_REQUEST['description'];
        $skill = $_REQUEST['skill'];
        $experience = $_REQUEST['experience'];
        $status = 1;


        $update = "UPDATE `job_postings` SET `title`='$title',`description`='$description',`requirements`='$requirement',`location`='$location',`salary`='$Salary',`skills`='$skill', `workexperience`='$experience' WHERE job_id = $jid";

        if($update){


            ?> <script>alert('Job updated successful');location.replace("<?php echo BASE_URL; ?>/employer/manage-jobs");</script><?php
        }
        $query = mysqli_query($conn,$update);
    }

?>