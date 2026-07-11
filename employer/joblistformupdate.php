<?php
session_start();
include '../database_configure.php';

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/employer/signin-page");
    exit;
}

$id = intval($_SESSION['username']);
$jid = isset($_GET['j_id']) ? intval($_GET['j_id']) : 0;

// Verify that the job posting belongs to the logged-in employer
$checkJob = mysqli_query($conn, "SELECT employer_id FROM job_postings WHERE job_id = $jid");
if (!$checkJob || mysqli_num_rows($checkJob) === 0) {
    echo "<script>alert('Job not found.'); location.replace('" . BASE_URL . "/employer/manage-jobs');</script>";
    exit;
}
$jobData = mysqli_fetch_assoc($checkJob);
if (intval($jobData['employer_id']) !== $id) {
    echo "<script>alert('Unauthorized action.'); location.replace('" . BASE_URL . "/employer/manage-jobs');</script>";
    exit;
}

$month = date('m');
$day = date('d');
$year = date('Y');

$today = $year . '-' . $month . '-' . $day;

if($_POST){

    $title = mysqli_real_escape_string($conn, $_REQUEST['title']);
    $location = mysqli_real_escape_string($conn, $_REQUEST['location']);
    $Salary = mysqli_real_escape_string($conn, $_REQUEST['salary']);
    $requirement = mysqli_real_escape_string($conn, $_REQUEST['requirements']);
    $description = mysqli_real_escape_string($conn, $_REQUEST['description']);
    $skill = mysqli_real_escape_string($conn, $_REQUEST['skill']);
    $experience = mysqli_real_escape_string($conn, $_REQUEST['experience']);
    $status = 1;

    $update = "UPDATE `job_postings` SET `title`='$title',`description`='$description',`requirements`='$requirement',`location`='$location',`salary`='$Salary',`skills`='$skill', `workexperience`='$experience' WHERE job_id = $jid";

    $query = mysqli_query($conn, $update);
    if($query){
        ?> <script>alert('Job updated successful');location.replace("<?php echo BASE_URL; ?>/employer/manage-jobs");</script><?php
    }
}
?>