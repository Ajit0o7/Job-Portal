<?php
session_start();
include '../database_configure.php';

if (!isset($_SESSION['username'])) {
    echo "<script type='text/javascript'>alert('Please sign in first'); location.replace('http://jobportal.loc/jobseeker/jobseekerHome');</script>";
    exit;
}

$username = $_SESSION['username'];

// Handle application withdrawal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw_job_id'])) {
    $jobIdToWithdraw = (int)$_POST['withdraw_job_id'];
    $deleteSql = "DELETE FROM application WHERE job_id = '$jobIdToWithdraw' AND Seeker_id = '$username'";
    if ($conn->query($deleteSql) === TRUE) {
        echo "<script>alert('Application withdrawn successfully.'); location.replace('appliedjobs');</script>";
        exit;
    } else {
        echo "<script>alert('Error withdrawing application. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="jobseeker-style.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>JobPortal Applied Jobs</title>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .applied-jobs-grid {
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)) !important;
        }
        @media (max-width: 768px) {
            .applied-jobs-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
<body>
    <div class="nav-container">
        <nav class="glass-nav">
            <div class="logo"> 
                <a href="<?php echo BASE_URL; ?>/home-page"><img src="../img/logo.png" alt="JobPortal Logo"></a>
            </div>
            
            <input type="checkbox" id="menu">
            <label for="menu" class="menu-button">
                <i class="fas fa-bars"></i>
            </label>
            
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="<?php echo BASE_URL; ?>/jobseeker/jobseekerHome" class="nav-link">Seekers Hub</a></li>      
                    <li><a href="joblist" class="nav-link">Find Job</a></li>
                    <li><a href="resumepage" class="nav-link">Resume Here</a></li>
                    <li><a href="salaryexpectation" class="nav-link">Expected Salary</a></li>
                    <li><a href="appliedjobs" class="nav-link active">Applied Jobs</a></li>
                </ul>
                
                <div class="nav-actions">
                    <?php if(!isset($_SESSION['username'])): ?>
                        <a href="signin-page" class="btn btn-primary rounded-pill px-4 fw-semibold emp-btn">Account</a>
                    <?php else: ?>
                        <a href="../logout" class="btn btn-outline-danger rounded-pill px-4 fw-semibold js-btn">Log out</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>

    <!-- Modern Header Section -->
    <section class="banner" style="padding-top: 140px; min-height: 40vh; padding-bottom: 4rem; justify-content: center; text-align: center;">
        <div class="banner-content" style="flex: none; padding-right: 0;">
            <span class="badge-pill" style="margin-bottom: 1rem;">📝 Track Your Progress</span>
            <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem;">Your <span class="text-gradient">Applied Jobs</span></h1>
            <p style="font-size: 1.2rem; color: var(--text-muted); max-width: 600px; margin: 0 auto;">Manage and track the status of the jobs you've recently applied for.</p>
        </div>
    </section>

    <div class="habt_container" style="display: block; max-width: 1200px; margin: 0 auto; padding: 2rem 5% 4rem 5%;">
        <div class="category-grid applied-jobs-grid">
            <?php
            $fetch = "SELECT a.job_id, a.date_applied, a.status as app_status, j.title, j.location, j.salary, e.Fullname_E 
                      FROM application a 
                      JOIN job_postings j ON a.job_id = j.job_id 
                      JOIN employerlogin e ON j.employer_id = e.employer_id 
                      WHERE a.Seeker_id = '$username' 
                      ORDER BY a.date_applied DESC";

            $queryfetch = mysqli_query($conn, $fetch);

            if (mysqli_num_rows($queryfetch) > 0) {
                while ($result = mysqli_fetch_assoc($queryfetch)) { 
                    $statusColor = $result['app_status'] == 'applied' ? 'var(--primary-color)' : 'var(--text-main)';
            ?>
                <div class="glass-card buy" style="align-items: flex-start; text-align: left; padding: 2.5rem; display: flex; flex-direction: column; justify-content: space-between;">
                    <div style="margin-bottom: 1.5rem; width: 100%;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <h3 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin: 0; padding-right: 15px; line-height: 1.3;"><?= htmlspecialchars($result['title']); ?></h3>
                            <span class="badge-pill" style="background: <?= $statusColor; ?>; color: white; padding: 6px 14px; font-size: 0.75rem; text-transform: capitalize; margin-bottom: 0; white-space: nowrap;"><?= htmlspecialchars($result['app_status']); ?></span>
                        </div>
                        <p style="color: var(--text-muted); margin-bottom: 0.6rem; font-size: 0.95rem;"><i class="fas fa-building" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($result['Fullname_E']); ?></p>
                        <p style="color: var(--text-muted); margin-bottom: 0.6rem; font-size: 0.95rem;"><i class="fas fa-money-bill-wave" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($result['salary']); ?></p>
                        <p style="color: var(--text-muted); margin-bottom: 0.6rem; font-size: 0.95rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color); width: 25px;"></i> <?= htmlspecialchars($result['location']); ?></p>
                        <p style="color: var(--text-muted); margin-bottom: 0; font-size: 0.9rem;"><i class="fas fa-calendar-alt" style="color: var(--primary-color); width: 25px;"></i> Applied on: <?= htmlspecialchars($result['date_applied']); ?></p>
                    </div>
                    
                    <div style="display: flex; gap: 12px; width: 100%; margin-top: 1rem; align-items: center;">
                        <a href="job/<?= $result['job_id']; ?>/<?= urlencode(strtolower(str_replace(' ', '-', $result['title']))); ?>" style="flex: 1; text-decoration: none;">
                            <button class="btn-primary rounded-pill w-100" style="padding: 10px; font-size: 0.95rem; font-weight: 600; border: none; height: 45px;">View Details</button>
                        </a>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to withdraw your application for this job?');" style="margin: 0;">
                            <input type="hidden" name="withdraw_job_id" value="<?= $result['job_id']; ?>">
                            <button type="submit" class="btn btn-outline-danger" title="Withdraw Application" style="width: 45px; height: 45px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: 1px solid rgba(220,53,69,0.3); background: rgba(220,53,69,0.05); color: #dc3545; transition: all 0.3s;" onmouseover="this.style.background='#dc3545'; this.style.color='white';" onmouseout="this.style.background='rgba(220,53,69,0.05)'; this.style.color='#dc3545';"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </div>
                </div>
            <?php } 
            } else {
                echo "<div style='text-align: center; width: 100%; grid-column: 1 / -1;'>
                        <i class='fas fa-folder-open' style='font-size: 4rem; color: var(--text-muted); margin-bottom: 1.5rem;'></i>
                        <h3 style='font-size: 1.8rem; color: var(--text-main); font-weight: 700; margin-bottom: 1rem;'>No Applications Found</h3>
                        <p style='color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto 2rem;'>You haven't applied to any jobs yet. Start exploring opportunities to find your next role.</p>
                        <a href='joblist' class='btn-primary rounded-pill' style='padding: 12px 30px; display: inline-block; text-decoration: none;'>Find Jobs</a>
                      </div>";
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php' ?>
</body>
</html>
