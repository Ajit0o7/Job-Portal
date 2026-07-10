<?php
session_start();
include '../database_configure.php';
$id = $_SESSION['username'];
    
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


        $insert = "INSERT INTO `job_postings`(`employer_id`, `title`, `description`, `requirements`, `location`, `salary`, `date_posted`,`skills`,`status`,`workexperience`) VALUES ('$id','$title','$description','$requirement','$location','$Salary','$today','$skill','$status','$experience')";

        $query = mysqli_query($conn,$insert);

        if($query){
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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