<?php
    session_start();  
    if(!isset($_SESSION["Adname"]))
    {
        header("location:index.php");
        exit;
    }

    include '../database_configure.php';
    
    
    $currentPage = 0;
    $searchlimit = 8;

    if(isset($_GET['page'])){
        if($_GET['page']<=0 || !is_numeric($_GET['page'])){
            $currentPage = 0;
        }else{
        $currentPage = ($_GET['page']-1)*$searchlimit;
        }
    }
    $fetch = "SELECT * FROM job_postings";
    $query = mysqli_query($conn,$fetch);
   

    $totalResult = mysqli_num_rows($query);
    if($totalResult>0){
        
        $pagination = ceil($totalResult / $searchlimit);

    $fetch2 = "SELECT job_postings.*, employerlogin.employer_id, employerlogin.Fullname_E, employerlogin.Email_Address_E FROM job_postings JOIN employerlogin ON job_postings.employer_id = employerlogin.employer_id ORDER by date_posted desc LIMIT  $currentPage,$searchlimit";
    $finalresult = mysqli_query($conn,$fetch2);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job List | JobPortal Admin</title>
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="adminstyle.css?v=1783694695" />
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
<body>
    <?php include 'logout.php'; ?>
    <div>
        <div class="navforadmin">
        <a class="navstyle " href="dash"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a class="navstyle navactive" href="viewjoblist"><i class="fas fa-briefcase"></i> View Job List</a>
        <a class="navstyle " href="viewemployerlist"><i class="fas fa-building"></i> View Employers</a>
        <a class="navstyle " href="viewtopskillsdemanded"><i class="fas fa-laptop-code"></i> Demanded Skills</a>
        <a class="navstyle " href="topuserskills"><i class="fas fa-graduation-cap"></i> Top User Skills</a>
    </div>
        <div class="admin-container">
        <div class="page-header">
            <h1>Job Postings</h1>
            <p>Review and monitor all active and closed job opportunities listed by employers.</p>
        </div>
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Posted Date</th>
                        <th>Company Name</th>
                        <th>Email Address</th>
                        <th>Location</th>
                        <th>Job Title</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($finalresult && mysqli_num_rows($finalresult) > 0) { ?>
                        <?php while($result = mysqli_fetch_assoc($finalresult)) { ?>
                            <tr>
                                <td><?php echo date("M d, Y", strtotime($result['date_posted'])); ?></td>
                                <td><strong><?= htmlspecialchars($result['Fullname_E']); ?></strong></td>
                                <td><?= htmlspecialchars($result['Email_Address_E']); ?></td> 
                                <td><i class="fas fa-map-marker-alt" style="color: #64748b; margin-right: 4px;"></i> <?= htmlspecialchars($result['location']); ?></td>
                                <td><?= htmlspecialchars($result['title']); ?></td>
                                <td>
                                    <?php echo ($result['status'] == 'open') ? "<span class='status-open'>On Search</span>" : "<span class='status-closed'>Closed</span>"; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 30px;">No job postings found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <div class="admin-pagination">
                <?php 
                    $page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    for($i = 1; $i <= $pagination; $i++) {
                        $active = ($i === $page_num) ? 'active' : '';
                        echo '<a class="' . $active . '" href="viewjoblist?page='.$i.'">'.$i.'</a>';
                    } 
                ?>
            </div>
        </div>
    </div>
    </div>
    <div class="footer">
        &copy; <?php echo date("Y"); ?> JobPortal. All rights reserved.
    </div>
</body>
</html>
