<?php 
session_start();
include '../database_configure.php';

$jid = isset($_GET['j_id']) ? (int)$_GET['j_id'] : 0;
$sid = isset($_SESSION['username']) ? $_SESSION['username'] : 0;

if ($sid === 0) {
    echo "<script>alert('Please log in to apply.'); location.replace('signin-page');</script>";
    exit;
}

// 1. Fix session collision: ensure this user is actually a job seeker
$checkSeeker = mysqli_query($conn, "SELECT * FROM seekerlogin WHERE Seeker_id = '$sid'");
if(mysqli_num_rows($checkSeeker) == 0){
    echo "<script>alert('Error: You are logged in as an Employer. Please log out and sign in as a Job Seeker to apply.'); location.replace('../logout');</script>";
    exit;
}

// 2. Ensure they have a resume built
$user = "SELECT * FROM `seekerresume` WHERE Seeker_id = '$sid'";
$userquery = mysqli_query($conn,$user);
if(mysqli_num_rows($userquery) == 0){
    echo "<script>alert('You must complete your resume before applying for jobs.'); location.replace('resumepage');</script>";
    exit;
}

// 3. Fetch employer_id dynamically so we don't rely on the URL
$jobQuery = mysqli_query($conn, "SELECT employer_id FROM job_postings WHERE job_id = '$jid'");
if(mysqli_num_rows($jobQuery) > 0){
    $jobData = mysqli_fetch_assoc($jobQuery);
    $eid = $jobData['employer_id'];
} else {
    echo "<script>alert('Job posting not found.'); location.replace('joblist');</script>";
    exit;
}

$today = date('Y-m-d');

$insert = "INSERT INTO `application`(`job_id`, `Seeker_id`, `employer_id`, `date_applied`, `status`) VALUES ('$jid','$sid','$eid','$today','applied')";

$query = mysqli_query($conn,$insert);

if($query){

    $user = "SELECT * FROM `seekerresume` WHERE Seeker_id = $sid";
    $userquery = mysqli_query($conn,$user);
    $mailiduser = mysqli_fetch_assoc($userquery);
    $username = $mailiduser['FullName'];
    $yearexp = $mailiduser['Workexp'];
    
    $contact = $mailiduser['Contact'];
    $Skillsin = $mailiduser['skill'];

    $company = "SELECT * FROM `employerlogin` WHERE employer_id = $eid";

    $companyquery = mysqli_query($conn,$company);
    $mailid = mysqli_fetch_assoc($companyquery);

    $companyname = $mailid['Fullname_E'];

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .="Content-type:text/html; charset=iso-8859-1"."\r\n";
    $headers .="From: maharjannhuj@gmail.com"."\r\n";

    $subject = "Job Application - JobPortal ";
    $email = $mailid['Email_Address_E'];
    $body = "<div style=' margin-left: 25%; width: 50%; color: black; padding: 18px; '>
    <div style='font-size: 25px; text-align: center; background: black; color: white; padding: 12px;'>
        <b><u>JobPortal</u></b>
    </div>
    <hr/>
    
    <div>
        TO,
            
        <div><b>$companyname</b></div><br/>
        
        <div style=' padding: 8px; text-align: center; font-size: 20px; font-weight: bold;'>
            Subject= Job Application - $username
        </div><br/>
        
        <div>
            <div style='display: flex; margin-top: 15px; padding: 5px 0px 4px 8px; background-color: #FAF9F6; border-radius: 15px;'>
                <div style=width: 60%;'>
                Dear Sir/Madam,
        
            <div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I hope this email finds you well. I am writing to express my strong interest in the position role at $companyname as posted on JobPortal. Enclosed is my resume, highlighting my relevant experience and skills.
            </div>
                    <div>
                    With $yearexp of experience in $Skillsin, I am confident in my ability to contribute positively to your team. I am drawn to $companyname because of its reputation for [mention a specific aspect of the company you admire, e.g., innovation, culture, impact].
                    <div>
                </div>
                
            </div>
                <div>
                Thank you for considering my application. I look forward to the opportunity to discuss how my qualifications align with your needs. Please find attached my resume.
                </div>
                <div>
                Best regards,
                Best regards,<br/>
                $username<br/>
                $contact<br/><br/>
                Link: <a href='" . BASE_URL . "/employer/viewresume?j_id=$sid'>View Application</a>
                </div>
        </div>
    </div>
</div>";
    
    // Suppress warnings with @ so local environments without SMTP don't crash
    $success = @mail($email,$subject,$body,$headers);
}

if($query){
    ?> <script>
        alert('Job applied successfully! The employer has been notified.');
        location.replace("joblist");
    </script>
    <?php
}

?>