<?php
    session_start();  
    if(!isset($_SESSION["Adname"]))
    {
        header("location:index.php");
        exit;
    }

    include '../database_configure.php';
    include 'logout.php';

    // Retrieve and process demanded skills data
    $sql = "SELECT * FROM job_postings";
    $result = $conn->query($sql);
    $skillsDemand = [];

    if ($result && $result->num_rows > 0) {
        while ($job = $result->fetch_assoc()) {
            $jobSkills = explode(', ', $job['skills']);
            foreach ($jobSkills as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    if (!isset($skillsDemand[$skill])) {
                        $skillsDemand[$skill] = 1;
                    } else {
                        $skillsDemand[$skill]++;
                    }
                }
            }
        }
    }
    arsort($skillsDemand);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demanded Skills | JobPortal Admin</title>
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="adminstyle.css?v=<?php echo time(); ?>" />
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
<body class="demanded-skills-page">
    <div class="navforadmin">
        <a class="navstyle" href="dash"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a class="navstyle" href="viewjoblist"><i class="fas fa-briefcase"></i> View Job List</a>
        <a class="navstyle" href="viewemployerlist"><i class="fas fa-building"></i> View Employers</a>
        <a class="navstyle navactive" href="viewtopskillsdemanded"><i class="fas fa-laptop-code"></i> Demanded Skills</a>
        <a class="navstyle" href="topuserskills"><i class="fas fa-graduation-cap"></i> Top User Skills</a>
    </div>

    <div class="admin-container">
        <div class="page-header">
            <h1>Demanded Skills</h1>
            <p>Analyze the most sought-after technical skills requested by employers in active job postings.</p>
        </div>
        
        <div class="chart-card">
            <div class="chart-container">
                <?php
                if (!empty($skillsDemand)) {
                    $maxDemand = max($skillsDemand);
                    foreach ($skillsDemand as $skill => $demand) {
                        $percentage = ($demand / $maxDemand) * 100;
                        ?>
                        <div class="chart-row">
                            <div class="skill-name"><?php echo htmlspecialchars($skill); ?></div>
                            <div class="bar-wrapper">
                                <div class="bar" style="width: <?php echo $percentage; ?>%;">
                                    <span class="bar-value"><?php echo $demand; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p style='text-align: center; color: #64748b; padding: 20px;'>No skill demand data available.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> JobPortal Admin. All rights reserved.
    </div>
</body>
</html>
