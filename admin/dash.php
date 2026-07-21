<?php
    session_start();  
    if(!isset($_SESSION["Adname"]))
    {
        header("location:index.php");
        exit;
    }

    include '../database_configure.php';
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | JobPortal Admin</title>
    
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="adminstyle.css?v=<?php echo time(); ?>" />
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
<body>
    <?php include 'logout.php'; ?>
    <div class="navforadmin">
        <a class="navstyle navactive" href="dash"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a class="navstyle " href="viewjoblist"><i class="fas fa-briefcase"></i> View Job List</a>
        <a class="navstyle " href="viewemployerlist"><i class="fas fa-building"></i> View Employers</a>
        <a class="navstyle " href="viewtopskillsdemanded"><i class="fas fa-laptop-code"></i> Demanded Skills</a>
        <a class="navstyle " href="topuserskills"><i class="fas fa-graduation-cap"></i> Top User Skills</a>
    </div>

    <div class="dashboard-header">
        <h1>Dashboard Overview</h1>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['Adname']); ?>! Here is the latest system activity overview.</p>
    </div>

    <div class="dashed">
        <!-- Job Seekers Card -->
        <div class="dashcard">
            <div class="card-content">
                <?php
                    $fetch = "SELECT COUNT(*) as totalseeker from seekerlogin";
                    $query = mysqli_query($conn, $fetch);
                    $result = mysqli_fetch_assoc($query);
                ?>
                <h2>Total Job Seekers</h2>
                <div class="dashinfo">
                    <?php echo number_format($result['totalseeker']); ?>
                </div>
            </div>
            <div class="card-icon seeker-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <!-- Employers Card -->
        <div class="dashcard">
            <div class="card-content">
                <?php
                    $fetchsale = "SELECT COUNT(*) as emp from employerlogin";
                    $querysale = mysqli_query($conn, $fetchsale);
                    $resultsale = mysqli_fetch_assoc($querysale);
                ?>
                <h2>Total Employers</h2>
                <div class="dashinfo">
                    <?php echo number_format($resultsale['emp']); ?>
                </div>
            </div>
            <div class="card-icon employer-icon">
                <i class="fas fa-building"></i>
            </div>
        </div>

        <!-- Jobs Listed Card -->
        <div class="dashcard">
            <div class="card-content">
                <?php
                    $fetchhouse = "SELECT COUNT(*) as joblist FROM job_postings";
                    $queryhouse = mysqli_query($conn, $fetchhouse);
                    $resulthouse = mysqli_fetch_assoc($queryhouse);
                ?>
                <h2>Total Jobs Listed</h2>
                <div class="dashinfo">
                    <?php echo number_format($resulthouse['joblist']); ?>
                </div>
            </div>
            <div class="card-icon job-icon">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>

        <!-- Jobs Applied Card -->
        <div class="dashcard">
            <div class="card-content">
                <?php
                    $fetchland = "SELECT COUNT(*) as aplica FROM application";
                    $queryland = mysqli_query($conn, $fetchland);
                    $resultland = mysqli_fetch_assoc($queryland);
                ?>
                <h2>Total Jobs Applied</h2>
                <div class="dashinfo">
                    <?php echo number_format($resultland['aplica']); ?>
                </div>
            </div>
            <div class="card-icon applied-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> JobPortal Admin. All rights reserved.
    </div>
</body>
</html>
