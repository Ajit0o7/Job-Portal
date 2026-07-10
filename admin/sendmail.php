<?php
    session_start();  
    if(!isset($_SESSION["Adname"])) {
        header("location:index.php");
    }

    include '../database_configure.php';
    

    $fetch = "SELECT * FROM `employerlogin` where `employer_id` = '$_REQUEST[user_id]'";
    $query = mysqli_query($conn, $fetch);
    $output = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobPortal:Mail</title>
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="adminstyle.css?v=1783693806" />
    <style>
        

        .fortable {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 8px;
            margin-bottom: 16px;
            resize: vertical;
        }

        textarea {
            height: 250px;
        }

        .submitbtnmail{
            width: 100%;
            background-color: #143d59;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        .submitbtnmail:hover {
            background-color: #4d9cff;
        }

        td {
            padding: 10px;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <?php include 'logout.php'; ?>
    <div class="navforadmin">
        <a class="navstyle " href="dash"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a class="navstyle " href="viewjoblist"><i class="fas fa-briefcase"></i> View Job List</a>
        <a class="navstyle " href="viewemployerlist"><i class="fas fa-building"></i> View Employers</a>
        <a class="navstyle " href="viewtopskillsdemanded"><i class="fas fa-laptop-code"></i> Demanded Skills</a>
        <a class="navstyle " href="topuserskills"><i class="fas fa-graduation-cap"></i> Top User Skills</a>
    </div>

    <div class="fortable">
        <div class="form-title">Send Email</div>
        <form method="POST">
            <table>
                <tr>
                    <td>TO:</td>
                    <td><input type="email" name="email" value="<?= $output['Email_Address_E'] ?>" readonly></td>
                </tr>
                <tr>
                    <td>Subject:</td>
                    <td><input type="text" name="subject"></td>
                </tr>
                <tr>
                    <td>Body:</td>
                    <td><textarea name="message"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Send Email" class="submitbtnmail"></td>
                </tr>
            </table>
        </form>
    </div>    
    <div class="footer">
        &copy; <?php echo date("Y"); ?> JobPortal. All rights reserved.
    </div>
</body>
</html>


<?php
if ($_POST) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
    $headers .= "From: maharjannhujan@gmail.com" . "\r\n";

    $subject = $_REQUEST["subject"];
    $email = $_REQUEST["email"];
    $body = $_REQUEST["message"];

    $success = mail($email, $subject, $body, $headers);
    if ($success) {
        echo "<script>alert('Mail sent');location.replace('" . BASE_URL . "/admin/viewjoblist');</script>";
    }
}
?>
