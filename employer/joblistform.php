<?php
session_start();
include '../database_configure.php';

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/employer/signin-page");
    exit;
}

$id = intval($_SESSION['username']);
    
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


        $insert = "INSERT INTO `job_postings`(`employer_id`, `title`, `description`, `requirements`, `location`, `salary`, `date_posted`,`skills`,`status`,`workexperience`) VALUES ('$id','$title','$description','$requirement','$location','$Salary','$today','$skill','$status','$experience')";

        $query = mysqli_query($conn,$insert);

        if($query){
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
            <body style="background: #f4f7fb;">
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Job Published Successfully!',
                        text: 'Your job vacancy is now live and visible to job seekers.',
                        confirmButtonColor: '#2ecc71',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.replace('manage-jobs');
                    });
                </script>
            </body>
            </html>
            <?php
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
?>