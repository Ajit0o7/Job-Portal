<?php 
session_start();
include '../database_configure.php';

if(!isset($_SESSION['username'])){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body style="background: #f4f7fb;">
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Authentication Required',
                text: 'Please sign in to your employer account first to view applicants.',
                confirmButtonColor: '#1171ba',
                allowOutsideClick: false
            }).then(() => {
                window.location.replace('employerHome');
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}
$eid12 = $_SESSION['username'];
$jid = isset($_GET['j_id']) ? intval($_GET['j_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo BASE_URL; ?>/employer/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>JobPortal - Job Applicants</title>
</head>
<body>
    <div class="nav-container">
        <nav class="glass-nav">
            <div class="logo"> 
                <a href="<?php echo BASE_URL; ?>/home-page"><img src="../img/logo.png" alt="JobPortal Logo"></a>
            </div>
            <input type="checkbox" id="menu">
            <label for="menu" class="menu-button"><i class="fas fa-bars"></i></label>
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="employerHome" class="nav-link">Employer Hub</a></li>      
                    <li><a href="listjob" class="nav-link">List Out Job</a></li>
                    <li><a href="manage-jobs" class="nav-link">Update Job</a></li>
                    <li><a href="checkapplication" class="nav-link active">Listed Jobs</a></li>
                </ul>
                <div class="nav-actions">
                    <a href="../logout" class="btn btn-outline-danger rounded-pill px-4 fw-semibold js-btn">Log out</a>
                </div>
            </div>
        </nav>
    </div>

    <?php 
    $jobTitleQuery = "SELECT j.title FROM job_postings AS j WHERE j.employer_id = $eid12 AND j.job_id = $jid";
    $jobTitleResult = mysqli_query($conn, $jobTitleQuery);
    
    if ($jobTitleResult && mysqli_num_rows($jobTitleResult) > 0) {
        $jobTitleRow = mysqli_fetch_assoc($jobTitleResult);
        $jobTitle = htmlspecialchars($jobTitleRow['title']);
    } else {
        $jobTitle = "Unknown Position";
    }
    ?>
    
    <div style="padding-top: 140px; padding-bottom: 4rem; background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%);">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <span class="badge-pill" style="margin-bottom: 1rem; background: rgba(42, 157, 244, 0.1); color: var(--primary-color);"><i class="fas fa-folder-open"></i> Application Inbox</span>
            <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--text-main);">Candidates For: <br><span class="text-gradient"><?= $jobTitle ?></span></h2>
            <a href="checkapplication" class="btn btn-outline-primary rounded-pill mt-3" style="border-width: 2px; padding: 8px 25px;"><i class="fas fa-arrow-left"></i> Back to All Jobs</a>
        </div>
    </div>

    <div style="background: var(--bg-color); padding: 4rem 5%; min-height: 50vh; max-width: 1400px; margin: 0 auto;">
        
        <!-- Standard Applications -->
        <div style="margin-bottom: 4rem;">
            <h3 style="font-size: 1.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem;"><i class="fas fa-inbox" style="color: var(--primary-color);"></i> All Received Applications</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                <?php 
                    $select = "SELECT s.Fullname_S, s.Email_S, j.title, j.description, j.location, j.date_posted, 
                                    r.sk_id, r.FullName, r.EmailAddress, r.Contact, r.Country, r.Provience, 
                                    r.City, r.Address, r.Education, r.Workexp, r.skill, a.status, s.Seeker_id 
                            FROM seekerlogin AS s 
                            JOIN application AS a ON s.Seeker_id = a.Seeker_id 
                            JOIN job_postings AS j ON a.job_id = j.job_id 
                            JOIN seekerresume AS r ON s.Seeker_id = r.Seeker_id 
                            WHERE a.employer_id = $eid12 AND j.job_id = $jid AND a.status='applied'";
                    
                    $query = mysqli_query($conn, $select);
                    if (mysqli_num_rows($query) == 0) {
                        echo "<div style='grid-column: 1 / -1; text-align: center; padding: 3rem; background: rgba(255,255,255,0.6); border-radius: 15px; border: 1px dashed var(--border-color); color: var(--text-muted);'><i class='fas fa-ghost' style='font-size: 3rem; margin-bottom: 1rem; color: #cbd5e1;'></i><p>No standard applications received yet.</p></div>";
                    } else {
                        while ($result = mysqli_fetch_assoc($query)) {
                ?>
                            <a href="candidate/<?php echo $result['Seeker_id'] ?>" style="text-decoration: none;">
                                <div class="glass-card hover-lift" style="padding: 2rem; border-radius: 15px; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem;">
                                        <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #1171ba, #2a9df4); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold;">
                                            <?= strtoupper(substr($result['Fullname_S'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h5 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: var(--text-main);"><?= htmlspecialchars($result['Fullname_S']) ?></h5>
                                            <span style="color: var(--text-muted); font-size: 0.85rem;"><i class="fas fa-envelope"></i> <?= htmlspecialchars($result['Email_S']) ?></span>
                                        </div>
                                    </div>
                                    <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; font-size: 0.9rem; color: var(--text-muted);">
                                        <i class="fas fa-calendar-check" style="color: #2ecc71;"></i> Applied: <?= date('M d, Y', strtotime($result['date_posted'])) ?>
                                    </div>
                                </div>
                            </a>
                <?php 
                        }
                    }
                ?>
            </div>
        </div>

        <!-- AI Top Candidates (Parser V2) -->
        <div style="margin-bottom: 4rem;">
            <h3 style="font-size: 1.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem;"><i class="fas fa-robot" style="color: #9b59b6;"></i> AI Matching: Top Candidates</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                <?php include('resume-parserv2.php'); ?>
            </div>
        </div>

        <!-- Recommended Candidates (Parser V1) -->
        <div style="margin-bottom: 4rem;">
            <h3 style="font-size: 1.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem;"><i class="fas fa-star" style="color: #f1c40f;"></i> System Recommended</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                <?php include('resume-parserv1.php'); ?>
            </div>
        </div>

    </div>

    <?php include 'footer.php' ?>
    <style>
        .hover-lift:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
            border-color: var(--primary-color);
        }
    </style>
</body>
</html>