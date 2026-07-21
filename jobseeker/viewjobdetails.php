<?php 
session_start();
include '../database_configure.php';

$is_logged_in = isset($_SESSION['username']);
$id = $is_logged_in ? $_SESSION['username'] : null;
$jid = isset($_GET['j_id']) ? (int)$_GET['j_id'] : (isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0);
$eid = isset($_GET['e_id']) ? (int)$_GET['e_id'] : (isset($_GET['employer_id']) ? (int)$_GET['employer_id'] : 0);

if ($jid === 0) {
    echo "<script>location.replace('joblist');</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo BASE_URL; ?>/jobseeker/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="jobseeker-style.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>JobPortal - Job Details</title>
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
                    <li><a href="joblist" class="nav-link active">Find Job</a></li>
                    <li><a href="resumepage" class="nav-link">Resume Here</a></li>
                    <li><a href="salaryexpectation" class="nav-link">Expected Salary</a></li>
                    <li><a href="appliedjobs" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'appliedjobs.php') ? 'active' : ''; ?>">Applied Jobs</a></li>
                </ul>
                <div class="nav-actions">
                    <?php if(!$is_logged_in): ?>
                        <a href="signin-page" class="btn btn-primary rounded-pill px-4 fw-semibold emp-btn">Account</a>
                    <?php else: ?>
                        <a href="../logout" class="btn btn-outline-danger rounded-pill px-4 fw-semibold js-btn">Log out</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
    
    <div style="padding-top: 140px; padding-bottom: 4rem; background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%); min-height: 80vh;">
        <div class="habt_container" style="display: flex; gap: 2rem; max-width: 1200px; margin: 0 auto; flex-wrap: wrap;">
            
            <!-- Left Column: Applied Jobs (Only if logged in) -->
            <div style="flex: 1; min-width: 300px; max-width: 400px;">
                <div class="glass-card" style="padding: 2rem;">
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;"><i class="fas fa-history" style="color: var(--primary-color);"></i> Your Applications</h3>
                    <?php 
                    if ($is_logged_in) {
                        $seekid = $_SESSION['username'];
                        $select = "SELECT a.employer_id, a.application_id, j.title, a.Seeker_id, e.Fullname_E, a.status, j.status AS job_status, j.job_id 
                                    FROM application AS a 
                                    JOIN job_postings AS j ON a.job_id = j.job_id 
                                    JOIN employerlogin AS e ON j.employer_id = e.employer_id 
                                    WHERE a.Seeker_id = '$seekid' AND a.status = 'applied'";
                        $runquery = mysqli_query($conn, $select);

                        if (mysqli_num_rows($runquery) > 0) {
                            while ($result = mysqli_fetch_assoc($runquery)) { ?>
                                <a href="job/<?= $result['job_id']; ?>/<?= urlencode(strtolower(str_replace(' ', '-', $result['title']))); ?>" style="text-decoration: none; display: block; margin-bottom: 1rem;">
                                    <div style="background: rgba(255,255,255,0.6); padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color); transition: var(--transition);" onmouseover="this.style.background='rgba(255,255,255,0.9)'; this.style.borderColor='var(--primary-color)';" onmouseout="this.style.background='rgba(255,255,255,0.6)'; this.style.borderColor='var(--border-color)';">
                                        <h4 style="font-size: 1.1rem; color: var(--text-main); font-weight: 600; margin-bottom: 0.2rem;"><?= $result['title']; ?></h4>
                                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;"><i class="fas fa-building" style="color: var(--primary-light);"></i> <?= $result['Fullname_E']; ?></p>
                                        <?php if ($result['job_status'] == 2) { ?>
                                            <p style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem; margin-bottom: 0;"><i class="fas fa-exclamation-circle"></i> Job Posting Removed</p>
                                        <?php } ?>
                                    </div>
                                </a>
                            <?php }
                        } else {
                            echo "<p style='color: var(--text-muted);'>You haven't applied to any jobs yet.</p>";
                        }
                    } else {
                        echo "<div style='text-align: center; padding: 1rem 0;'>";
                        echo "<p style='color: var(--text-muted); margin-bottom: 1rem;'>Sign in to track your job applications.</p>";
                        echo "<a href='signin-page' class='btn-primary rounded-pill' style='padding: 8px 20px; font-size: 0.9rem; text-decoration: none;'>Sign In</a>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Right Column: Job Details -->
            <div style="flex: 2; min-width: 300px;">
                <div class="glass-card" style="padding: 3rem;">
                    <?php
                    $selectJob = "SELECT j.job_id, j.title, j.location, j.salary, j.workexperience, j.description, j.requirements, j.skills, j.date_posted, e.employer_id, e.Fullname_E 
                                FROM job_postings AS j 
                                JOIN employerlogin AS e ON j.employer_id = e.employer_id 
                                WHERE j.job_id = $jid"; 
                    $runqueryJob = mysqli_query($conn, $selectJob);

                    if (mysqli_num_rows($runqueryJob) > 0) {
                        while ($result = mysqli_fetch_assoc($runqueryJob)) {
                            $title = $result['title'];
                            $employer = $result['employer_id'];
                            $description = $result['description'];
                            $requirements = $result['requirements'];
                            $location = $result['location'];
                            $salary = $result['salary'];
                            $skill = $result['skills'];
                            $wkexp = $result['workexperience'];
                            $datePosted = $result['date_posted'];
                            $company = $result['Fullname_E'];
                    ?>
                    
                    <div style="margin-bottom: 2rem;">
                        <span class="badge-pill" style="margin-bottom: 1rem; background: rgba(46, 204, 113, 0.1); color: var(--success);"><i class="fas fa-clock"></i> Posted: <?= $datePosted; ?></span>
                        <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;"><?= $title; ?></h1>
                        <h3 style="font-size: 1.3rem; color: var(--primary-color); font-weight: 600; margin-bottom: 1.5rem;"><i class="fas fa-building"></i> <?= $company; ?></h3>
                        
                        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 2rem;">
                            <div style="background: rgba(255,255,255,0.6); padding: 10px 20px; border-radius: 50px; border: 1px solid var(--border-color); color: var(--text-muted);"><i class="fas fa-map-marker-alt" style="color: var(--primary-color);"></i> <?= $location; ?></div>
                            <div style="background: rgba(255,255,255,0.6); padding: 10px 20px; border-radius: 50px; border: 1px solid var(--border-color); color: var(--text-muted);"><i class="fas fa-money-bill-wave" style="color: var(--success);"></i> <?= $salary; ?></div>
                            <div style="background: rgba(255,255,255,0.6); padding: 10px 20px; border-radius: 50px; border: 1px solid var(--border-color); color: var(--text-muted);"><i class="fas fa-briefcase" style="color: var(--primary-color);"></i> <?= $wkexp; ?> Years Exp.</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 2.5rem;">
                        <h4 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;">Job Description</h4>
                        <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.8; white-space: pre-wrap;"><?= $description; ?></p>
                    </div>

                    <div style="margin-bottom: 2.5rem;">
                        <h4 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;">Requirements</h4>
                        <ul style="list-style: none; padding-left: 0; color: var(--text-muted); font-size: 1.05rem; line-height: 1.8;">
                            <li><i class="fas fa-check-circle" style="color: var(--success); margin-right: 10px;"></i><?= $wkexp; ?> Years of Experience required.</li>
                            <?php
                            $requirementList = array_filter(array_map('trim', explode(',', $requirements)));
                            foreach ($requirementList as $requirement) {
                                echo "<li><i class='fas fa-check-circle' style='color: var(--success); margin-right: 10px;'></i>" . htmlspecialchars($requirement) . "</li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <div style="margin-bottom: 3rem;">
                        <h4 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;">Required Skills</h4>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <?php
                            $skillsList = array_filter(array_map('trim', explode(',', $skill)));
                            foreach ($skillsList as $individualSkill) {
                                echo "<span class='badge-pill' style='background: var(--primary-color); color: white;'>" . htmlspecialchars($individualSkill) . "</span>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="banner-buttons" style="border-top: 1px solid var(--border-color); padding-top: 2rem;">
                        <?php
                        $has_applied = false;
                        $app_id = 0;
                        if ($is_logged_in) {
                            $jobapplied = "SELECT application_id FROM `application` WHERE job_id = $jid AND Seeker_id = '$id' AND status = 'applied'";
                            $appliedquery = mysqli_query($conn, $jobapplied);
                            if (mysqli_num_rows($appliedquery) > 0) {
                                $has_applied = true;
                                $getid = mysqli_fetch_assoc($appliedquery);
                                $app_id = $getid['application_id'];
                            }
                        }

                        if ($has_applied) { ?>
                            <a href="application/<?= $jid; ?>/<?= $app_id; ?>" style="text-decoration: none;">
                                <button class="btn-secondary rounded-pill" style="padding: 15px 40px; font-size: 1.1rem;"><i class="fas fa-edit"></i> Edit Application</button>
                            </a>
                            <p style="color: var(--success); font-weight: 600; margin-top: 1rem;"><i class="fas fa-check-circle"></i> You have already applied for this role.</p>
                        <?php } else { ?>
                            <a href="apply?j_id=<?= $jid; ?>&e_id=<?= $employer; ?>" style="text-decoration: none;">
                                <button class="btn-primary rounded-pill" style="padding: 15px 40px; font-size: 1.1rem;"><i class="fas fa-paper-plane"></i> Apply Now</button>
                            </a>
                        <?php } ?>
                    </div>

                    <?php } 
                    } else {
                        echo "<h2 style='text-align: center; color: var(--text-muted); padding: 3rem 0;'>Job details not found or removed.</h2>";
                    }
                    ?>
                </div>
            </div>
            
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>