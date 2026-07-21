<?php 
session_start();
include '../database_configure.php';

if(!isset($_SESSION['username'])){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
    <body style="background: #f4f7fb;">
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Authentication Required',
                text: 'Please sign in to your employer account first to view applications.',
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>JobPortal - Track Applications</title>
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
                    <li><a href="<?php echo BASE_URL; ?>/employer/employerHome" class="nav-link">Employer Hub</a></li>      
                    <li><a href="listjob" class="nav-link">List Out Job</a></li>
                    <?php if(isset($_SESSION['username'])): ?>
                        <li><a href="manage-jobs" class="nav-link">Update Job</a></li>
                        <li><a href="checkapplication" class="nav-link active">Listed Jobs</a></li>
                    <?php endif; ?>
                </ul>
                
                <div class="nav-actions">
                    <a href="../logout" class="btn btn-outline-danger rounded-pill px-4 fw-semibold js-btn">Log out</a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Header Section -->
    <div style="padding-top: 140px; padding-bottom: 4rem; background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%); min-height: 85vh;">
        
        <div style="text-align: center; margin-bottom: 4rem;">
            <span class="badge-pill" style="margin-bottom: 1rem; background: rgba(42, 157, 244, 0.1); color: var(--primary-color);"><i class="fas fa-users"></i> Applicant Tracking</span>
            <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--text-main);">Review <span class="text-gradient">Candidate Applications</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Select any of your active job listings below to view all received applications and applicant resumes.</p>
        </div>

        <div style="max-width: 1200px; margin: 0 auto; padding: 0 5%; display: flex; flex-wrap: wrap; gap: 3rem;">
            
            <!-- Left Side - Job Cards -->
            <div style="flex: 2; min-width: 300px; display: flex; flex-direction: column; gap: 1.5rem;">
                <?php 
                $id = $_SESSION['username'];

                $select = "SELECT DISTINCT j.title, j.job_id, j.description, j.location, j.date_posted, 
                            s.Fullname_S, s.Email_S, r.FullName, r.EmailAddress, 
                            r.Contact, r.Country, r.Provience, r.City, r.Address, 
                            r.Education, r.Workexp, r.skill 
                            FROM job_postings AS j 
                            LEFT JOIN application AS a ON j.job_id = a.job_id AND a.employer_id = $id 
                            LEFT JOIN seekerlogin AS s ON a.Seeker_id = s.Seeker_id 
                            LEFT JOIN seekerresume AS r ON s.Seeker_id = r.Seeker_id 
                            WHERE j.employer_id = $id AND j.status = 'open' ORDER BY j.job_id DESC";

                $query = mysqli_query($conn, $select);
                $displayed_job_ids = [];

                if (mysqli_num_rows($query) > 0) {
                    while($result = mysqli_fetch_assoc($query)) {
                        if (!in_array($result['job_id'], $displayed_job_ids)) {
                            $displayed_job_ids[] = $result['job_id'];
                            ?>
                            <a href="applications/<?php echo $result['job_id'] ?>" style="text-decoration: none;">
                                <div class="glass-card" style="padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between; border-left: 5px solid var(--primary-color); transition: all 0.3s ease;" onmouseover="this.style.transform='translateX(10px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                                    <div>
                                        <h3 style="font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;"><?= htmlspecialchars($result['title']) ?></h3>
                                        <span style="color: var(--text-muted); font-size: 0.95rem;"><i class="fas fa-calendar-alt" style="margin-right: 5px;"></i> Posted: <?= date('M d, Y', strtotime($result['date_posted'])) ?></span>
                                    </div>
                                    <div style="background: rgba(42, 157, 244, 0.1); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.2rem;">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    }
                }
                
                if (count($displayed_job_ids) == 0) {
                    ?>
                    <div style="text-align: center; padding: 4rem; background: rgba(255,255,255,0.6); border-radius: 20px; border: 1px dashed var(--border-color);">
                        <i class="fas fa-inbox" style="font-size: 4rem; color: var(--border-color); margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--text-main); font-weight: 700;">No Jobs Posted</h3>
                        <p style="color: var(--text-muted); margin-bottom: 2rem;">You haven't posted any jobs to receive applications yet.</p>
                        <a href="listjob" class="btn btn-primary rounded-pill px-4"><i class="fas fa-plus"></i> Post a Job</a>
                    </div>
                    <?php
                }
                ?>
            </div>

            <!-- Right Side - Info Card -->
            <div style="flex: 1; min-width: 300px;">
                <div class="glass-card" style="padding: 3rem; text-align: center; background: linear-gradient(135deg, #1171ba, #2a9df4); color: white; border: none; position: sticky; top: 120px; box-shadow: 0 20px 40px rgba(17, 113, 186, 0.3);">
                    <div style="background: rgba(255,255,255,0.2); width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; backdrop-filter: blur(10px);">
                        <i class="fas fa-file-contract" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <h3 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 1rem;">Manage Applicants</h3>
                    <p style="font-size: 1.1rem; opacity: 0.9; line-height: 1.6; margin-bottom: 0;">
                        Click on any of your active job listings to view all received applications. You can review full candidate profiles, download resumes, and find the perfect fit for your team.
                    </p>
                </div>
            </div>
            
        </div>
    </div>

    <?php include 'footer.php' ?>
</body>
</html>