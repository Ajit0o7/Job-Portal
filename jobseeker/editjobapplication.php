<?php 
session_start();
include '../database_configure.php';

if(!isset($_SESSION['username'])){
    ?><script type='text/javascript'>alert('Please sign in first'); location.replace("<?php echo BASE_URL; ?>/jobseeker/joblist");</script><?php
    exit;
}
$id = intval($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="<?php echo BASE_URL; ?>/jobseeker/">
<link rel="stylesheet" href="jobseeker-style.css?v=<?php echo time(); ?>"/>
<link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>JobPortal Seekers Hub</title>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
    <style>
        .banner-header {
            padding-top: 140px;
            padding-bottom: 3rem;
            text-align: center;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .detail-item {
            background: rgba(255,255,255,0.5);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }
        .detail-item h4 {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }
        .detail-item p {
            font-size: 1.1rem;
            color: var(--text-main);
            font-weight: 600;
            margin-bottom: 0;
        }
    </style>
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
                    <li><a href="<?php echo BASE_URL; ?>/jobseeker/jobseekerHome" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'jobseekerHome.php') ? 'active' : ''; ?>">Seekers Hub</a></li>      
                    <li><a href="joblist" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'joblist.php') ? 'active' : ''; ?>">Find Job</a></li>
                    <li><a href="resumepage" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'resumepage.php' || basename($_SERVER['PHP_SELF']) == 'managecv.php' || basename($_SERVER['PHP_SELF']) == 'resumeform.php') ? 'active' : ''; ?>">Resume Here</a></li>
                    <li><a href="salaryexpectation" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'salaryexpectation.php') ? 'active' : ''; ?>">Expected Salary</a></li>
                    <li><a href="appliedjobs" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'appliedjobs.php') ? 'active' : ''; ?>">Applied Jobs</a></li>
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
           
<div style="background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%); min-height: 80vh; padding-bottom: 5rem;">
    <div class="banner-header">
        <span class="badge-pill" style="margin-bottom: 1rem; background: rgba(46, 204, 113, 0.1); color: var(--success);"><i class="fas fa-check-circle"></i> Application Active</span>
        <h1 style="font-size: 3rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">Manage <span class="text-gradient">Application</span></h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Review the details of the job you applied for, or withdraw your application.</p>
    </div>
           
    <div style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        <div class="glass-card" style="padding: 3rem;">
            <?php
            $jid = isset($_GET['j_id']) ? intval($_GET['j_id']) : 0;
            $eid = isset($_GET['e_id']) ? intval($_GET['e_id']) : 0;
            $aid = isset($_GET['a_id']) ? intval($_GET['a_id']) : 0;
            
            // Verify that this application belongs to the logged-in user and matches the job
            $checkApp = mysqli_query($conn, "SELECT application_id FROM application WHERE application_id = $aid AND Seeker_id = $id AND job_id = $jid AND status = 'applied'");
            if (!$checkApp || mysqli_num_rows($checkApp) === 0) {
                ?><script type='text/javascript'>alert('Application not found or unauthorized'); location.replace("<?php echo BASE_URL; ?>/jobseeker/joblist");</script><?php
                exit;
            }

            $select = "SELECT j.job_id, j.title, j.location, j.salary, j.description, j.requirements, j.skills, j.date_posted, e.employer_id, e.Fullname_E 
                       FROM job_postings AS j 
                       JOIN employerlogin AS e ON j.employer_id = e.employer_id 
                       WHERE j.job_id = $jid"; 

            $runquery = mysqli_query($conn, $select);

            if (mysqli_num_rows($runquery) > 0) {
                $result = mysqli_fetch_assoc($runquery);
                $title = $result['title'];
                $company = $result['Fullname_E'];
                $location = $result['location'];
                $salary = $result['salary'];
                $datePosted = $result['date_posted'];
                ?>
                
                <div style="text-align: center; margin-bottom: 3rem;">
                    <h2 style="font-size: 2.2rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;"><?= $title; ?></h2>
                    <h3 style="font-size: 1.3rem; color: var(--primary-color); font-weight: 600;"><i class="fas fa-building"></i> <?= $company; ?></h3>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <h4><i class="fas fa-map-marker-alt"></i> Location</h4>
                        <p><?= $location; ?></p>
                    </div>
                    <div class="detail-item">
                        <h4><i class="fas fa-money-bill-wave"></i> Salary</h4>
                        <p><?= $salary; ?></p>
                    </div>
                    <div class="detail-item">
                        <h4><i class="fas fa-calendar-alt"></i> Date Posted</h4>
                        <p><?= $datePosted; ?></p>
                    </div>
                </div>

                <div style="margin-bottom: 3rem; background: rgba(255,255,255,0.3); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;"><i class="fas fa-info-circle" style="color: var(--primary-light);"></i> Application Status</h4>
                    <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6; margin-bottom: 0;">Your resume and cover letter have been successfully submitted to <strong><?= $company; ?></strong>. If you made a mistake or no longer wish to be considered for this role, you can withdraw your application below.</p>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: center; border-top: 1px solid var(--border-color); padding-top: 2rem;">
                    <a href="job/<?= $jid; ?>/<?= urlencode(strtolower(str_replace(' ', '-', $title))); ?>" style="text-decoration: none;">
                        <button class="btn-primary rounded-pill" style="padding: 12px 30px; font-weight: 600;"><i class="fas fa-arrow-left"></i> Back to Job Details</button>
                    </a>
                    <button onclick="confirmWithdraw(<?= $aid; ?>)" class="btn-outline-danger rounded-pill" style="padding: 12px 30px; font-weight: 600; background: transparent; border: 2px solid #ef4444; color: #ef4444; transition: all 0.3s ease;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#ef4444';">
                        <i class="fas fa-trash-alt"></i> Withdraw Application
                    </button>
                </div>

                <?php
            } else {
                echo "<div style='text-align: center; padding: 3rem;'><h2 style='color: var(--text-muted);'>Job posting not found.</h2></div>";
            }
            ?>
        </div>
    </div>
</div>

<script>
function confirmWithdraw(aid) {
    Swal.fire({
        title: 'Withdraw Application?',
        text: "Are you sure you want to withdraw your application? This action cannot be undone and the employer will no longer see your resume.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Withdraw It',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `withdraw/${aid}`;
        }
    });
}
</script>
<?php include 'footer.php'; ?>