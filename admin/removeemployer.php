<?php
    session_start();  
    if(!isset($_SESSION["Adname"]))
    {
        header("location:index.php");
        exit;
    }

    include '../database_configure.php' ;
    
    $s_id = isset($_REQUEST['s_id']) ? intval($_REQUEST['s_id']) : 0;
    $fetch = "SELECT * FROM `employerlogin` where   `employer_id` = '$s_id'";
    $query = mysqli_query($conn,$fetch);
    $output = mysqli_fetch_assoc($query);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="adminstyle.css?v=1783693806" />
        <title>JobPortal:remove</title>
        <style>
.job-viewport-container {
  display: flex;
  justify-content: center;
  margin:10px;
}

.job-viewport {
  max-width: 800px;
  border:1px solid black;
  border-radius: 5px;
}

.job-card {
  border-radius: 5px;
  padding: 10px;
  margin-bottom: 20px;
  width: 100%;
  box-sizing: border-box;
  word-wrap: break-word;
}

.job-card h3 {
  margin-top: 0;
}

.job-card p {
  margin-bottom: 5px;
  font-size: 22px;;
}
.bannertextstyle1{
    color: black;
    font-size: 50px;
    text-align: center;
    padding-top: 30px;
    text-decoration: underline;
}

.card-link {
  text-decoration: none;
}

.card {
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 5px;
  margin-bottom: 5px;
  margin-top: 5px;
}

.card-body {
  padding: 10px;
}

.designation, .posted-date {
  margin: 0;
  font-weight: bold;
}

.designation {
    color: black;
}

.posted-date {
  color: black;
}
.containeremp{
    display: flex;
    padding: 34px;
  }

  .column {
    padding: 10px;
  }

  .column-70 {
    width: 80%;
  }

  .column-30 {
    width: 20%;
  }
  .bannertextstyle12{
    text-align: center;
  }

</style>
    </head>
    <body>
    <?php include 'logout.php'; ?>
        <div>
            <div class="navforadmin">
        <a class="navstyle " href="dash"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a class="navstyle " href="viewjoblist"><i class="fas fa-briefcase"></i> View Job List</a>
        <a class="navstyle navactive" href="viewemployerlist"><i class="fas fa-building"></i> View Employers</a>
        <a class="navstyle " href="viewtopskillsdemanded"><i class="fas fa-laptop-code"></i> Demanded Skills</a>
        <a class="navstyle " href="topuserskills"><i class="fas fa-graduation-cap"></i> Top User Skills</a>
    </div>
            <div class="removeproperty">
                <div class="sureremove" style="height:50vh; margin-top:35px;border-radius:5px;">
                <div class="job-card">
                        <p><span style="font-weight:bold;">Company Name:</span> <?php echo $output['Fullname_E']; ?></p>
                        <p><span style="font-weight:bold;">Email Address:</span> <?php echo $output['Email_Address_E']; ?></p>
                        <p><span style="font-weight:bold;">Location:</span> <?php echo $output['Location']; ?></p>
                        <p><span style="font-weight:bold;">Contact:</span><?php echo $output['Contact']; ?></p>
                    <a href="deleteemployee?s_id=<?php echo $output['employer_id']?>"><button style="background-color:red;color:white;font-size:22px;border-radius:5px;margin-left:40%;margin-top:25px;">Remove</button></a>
                </div>
            </div>
        </div>
    </body>
</html>