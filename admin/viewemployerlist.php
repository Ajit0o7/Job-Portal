<?php
    session_start();  
    if(!isset($_SESSION["Adname"]))
    {
    header("location:index.php");
    }

    include '../database_configure.php' ;
    
    
        $currentPage = 0;
        $searchlimit = 7;
    
        if(isset($_GET['page'])){
            if($_GET['page']<=0 || !is_numeric($_GET['page'])){
                $currentPage = 0;
            }else{
            $currentPage = ($_GET['page']-1)*$searchlimit;
            }
        }
        $fetch = "SELECT * FROM `employerlogin`";
        $query = mysqli_query($conn,$fetch);
       

        $totalResult = mysqli_num_rows($query);
        if($totalResult>0){
            
            $pagination = ceil($totalResult / $searchlimit);

        $fetch2 = "SELECT * FROM `employerlogin` Order BY employer_id desc LIMIT  $currentPage,$searchlimit ";
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
            <div class="admin-container">
        <div class="page-header">
            <h1>Employers Directory</h1>
            <p>View and manage all registered employer accounts and company details.</p>
        </div>
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Email Address</th>
                        <th>Location</th>
                        <th>Contact Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($finalresult && mysqli_num_rows($finalresult) > 0) { ?>
                        <?php while($result = mysqli_fetch_assoc($finalresult)) { ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($result['Fullname_E']); ?></strong></td>
                                <td><?= htmlspecialchars($result['Email_Address_E']); ?></td> 
                                <td><i class="fas fa-map-marker-alt" style="color: #64748b; margin-right: 4px;"></i> <?= htmlspecialchars($result['Location']); ?></td>
                                <td><i class="fas fa-phone" style="color: #64748b; margin-right: 4px;"></i> <?= htmlspecialchars($result['Contact']); ?></td>
                                <td>
                                    <a href="removeemployer?employer_id=<?php echo $result['employer_id']; ?>" class="abutton" style="background-color: #ef4444; color: #ffffff; border-color: #ef4444; font-size: 0.8rem; padding: 4px 10px; display: inline-flex; gap: 4px; box-shadow: none;" onclick="return confirm('Are you sure you want to remove this employer?')"><i class="fas fa-trash-alt"></i> Remove</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 30px;">No registered employers found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <div class="admin-pagination">
                <?php 
                    $page_num = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    for($i = 1; $i <= $pagination; $i++) {
                        $active = ($i === $page_num) ? 'active' : '';
                        echo '<a class="' . $active . '" href="viewemployerlist?page='.$i.'">'.$i.'</a>';
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