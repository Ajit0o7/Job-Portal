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
                text: 'Please sign in to your employer account first to view resumes.',
                confirmButtonColor: '#1171ba',
                allowOutsideClick: false
            }).then(() => {
                window.location.replace('<?php echo BASE_URL; ?>/employer/employerHome');
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}
$eid12 = $_SESSION['username'];
$candidate_id = isset($_GET['candidate_id']) ? intval($_GET['candidate_id']) : (isset($_GET['j_id']) ? intval($_GET['j_id']) : 0);
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
    <title>JobPortal - Candidate Profile</title>
</head>
<body style="background-color: #f8fafc;">
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

    <!-- Header Section -->
    <div style="padding-top: 140px; padding-bottom: 8rem; background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%);">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <span class="badge-pill" style="margin-bottom: 1rem; background: rgba(42, 157, 244, 0.1); color: var(--primary-color);"><i class="fas fa-id-badge"></i> Candidate Profile</span>
            <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--text-main);">Resume & <span class="text-gradient">Details</span></h2>
            <button onclick="window.history.back()" class="btn btn-outline-primary rounded-pill mt-3" style="border-width: 2px; padding: 8px 25px;"><i class="fas fa-arrow-left"></i> Back to Applicants</button>
        </div>
    </div>

    <!-- Main Content -->
    <div style="max-width: 1100px; margin: -5rem auto 4rem; padding: 0 5%; position: relative; z-index: 10;">
        <?php 
            $select = "SELECT * FROM `seekerresume` WHERE Seeker_id = $candidate_id";
            $runquery = mysqli_query($conn, $select);

            if(mysqli_num_rows($runquery) > 0) {
                while($result = mysqli_fetch_assoc($runquery)){
                    $avatar = !empty($result['image']) ? "../img/" . htmlspecialchars($result['image']) : "https://ui-avatars.com/api/?name=".urlencode($result['FullName'])."&background=random";
        ?>
        
        <div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
            <!-- Textured Profile Header Banner -->
            <div style="height: 140px; background: linear-gradient(135deg, #1171ba, #2a9df4); background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 20px 20px;"></div>
            
            <div style="padding: 0 3rem 4rem; text-align: center;">
                <!-- Centered Profile Info -->
                <div style="margin-top: -70px; margin-bottom: 2rem;">
                    <img src="<?= $avatar ?>" alt="Profile Picture" style="width: 140px; height: 140px; border-radius: 50%; border: 6px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.1); object-fit: cover; background: white; margin: 0 auto;">
                    <h1 style="font-size: 2.4rem; font-weight: 800; color: var(--text-main); margin: 15px 0 5px;"><?= htmlspecialchars($result['FullName']); ?></h1>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin: 0;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color);"></i> <?= htmlspecialchars($result['Address']); ?>, <?= htmlspecialchars($result['City']); ?>, <?= htmlspecialchars($result['Country']); ?></p>
                    
                    <div style="display: flex; justify-content: center; gap: 15px; margin-top: 1.5rem;">
                        <a href="mailto:<?= htmlspecialchars($result['EmailAddress']); ?>" class="btn btn-primary rounded-pill px-4 py-2" style="font-weight: 600;"><i class="fas fa-envelope"></i> Email Candidate</a>
                        <a href="tel:<?= htmlspecialchars($result['Contact']); ?>" class="btn btn-outline-primary rounded-pill px-4 py-2" style="font-weight: 600;"><i class="fas fa-phone"></i> Call Candidate</a>
                    </div>
                </div>

                <hr style="border-color: rgba(0,0,0,0.05); margin: 3rem 0;">

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; text-align: left;">
                    <!-- Left Column: Details -->
                    <div>
                        <div style="margin-bottom: 3rem;">
                            <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; padding-bottom: 10px; border-bottom: 2px solid rgba(0,0,0,0.05);">
                                <i class="fas fa-graduation-cap" style="color: var(--primary-color);"></i> Education
                            </h3>
                            <div style="position: relative; padding-left: 20px; border-left: 3px solid var(--primary-light);">
                                <div style="position: absolute; width: 15px; height: 15px; border-radius: 50%; background: var(--primary-color); left: -9px; top: 0; box-shadow: 0 0 0 4px white;"></div>
                                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.7; margin: 0;">
                                    <?= nl2br(htmlspecialchars($result['Education'])); ?>
                                </p>
                            </div>
                        </div>

                        <div style="margin-bottom: 3rem;">
                            <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; padding-bottom: 10px; border-bottom: 2px solid rgba(0,0,0,0.05);">
                                <i class="fas fa-briefcase" style="color: #2ecc71;"></i> Work Experience
                            </h3>
                            <div style="position: relative; padding-left: 20px; border-left: 3px solid rgba(46, 204, 113, 0.2);">
                                <div style="position: absolute; width: 15px; height: 15px; border-radius: 50%; background: #2ecc71; left: -9px; top: 0; box-shadow: 0 0 0 4px white;"></div>
                                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.7; margin: 0;">
                                    <?= nl2br(htmlspecialchars($result['Workexp'])); ?>
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; padding-bottom: 10px; border-bottom: 2px solid rgba(0,0,0,0.05);">
                                <i class="fas fa-laptop-code" style="color: #9b59b6;"></i> Technical Skills
                            </h3>
                            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                                <?php 
                                    $skills = explode(',', $result['skill']);
                                    foreach($skills as $skill) {
                                        if(trim($skill) != '') {
                                            echo '<span style="background: rgba(155, 89, 182, 0.08); border: 1px solid rgba(155, 89, 182, 0.2); color: #8e44ad; padding: 6px 16px; border-radius: 20px; font-weight: 600; font-size: 0.95rem;"><i class="fas fa-check" style="margin-right: 6px; font-size: 0.8rem;"></i> '.htmlspecialchars(trim($skill)).'</span>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Documents -->
                    <div>
                        <div style="background: #f8fafc; border-radius: 15px; padding: 2rem; border: 1px solid var(--border-color); position: sticky; top: 120px;">
                            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; text-align: center;">Attached Documents</h3>
                            
                            <?php if(!empty($result['pdffile'])) { ?>
                                <a href="../img/<?= htmlspecialchars($result['pdffile']); ?>" target="_blank" style="text-decoration: none;">
                                    <div style="background: white; padding: 1.5rem; border-radius: 12px; display: flex; flex-direction: column; align-items: center; gap: 10px; border: 2px dashed #cbd5e1; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary-color)'; this.style.backgroundColor='rgba(42,157,244,0.05)';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='white';">
                                        <i class="fas fa-file-pdf" style="font-size: 3rem; color: #e74c3c;"></i>
                                        <h5 style="margin: 0; font-size: 1.1rem; color: var(--text-main); font-weight: 600;">Original Resume</h5>
                                        <span style="color: var(--text-muted); font-size: 0.85rem;">Click to View PDF</span>
                                    </div>
                                </a>
                            <?php } else { ?>
                                <div style="text-align: center; color: var(--text-muted); padding: 2rem 0;">
                                    <i class="fas fa-file-excel" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                                    <p style="margin: 0;">No document attached</p>
                                </div>
                            <?php } ?>

                            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                                <h4 style="font-size: 1rem; color: var(--text-main); font-weight: 600; margin-bottom: 10px;">Contact Info</h4>
                                <div style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 10px;"><i class="fas fa-envelope" style="width: 20px; color: var(--primary-light);"></i> <?= htmlspecialchars($result['EmailAddress']); ?></div>
                                <div style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 10px;"><i class="fas fa-phone" style="width: 20px; color: var(--primary-light);"></i> <?= htmlspecialchars($result['Contact']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <?php 
                }
            } else {
        ?>
            <div class="glass-card" style="text-align: center; padding: 5rem 2rem;">
                <i class="fas fa-user-slash" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <h2 style="font-size: 2rem; font-weight: 700; color: var(--text-main);">Candidate Not Found</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 2rem;">The resume you are looking for does not exist or has been removed.</p>
                <button onclick="window.history.back()" class="btn btn-primary rounded-pill px-4"><i class="fas fa-arrow-left"></i> Go Back</button>
            </div>
        <?php } ?>
    </div>

    <?php include 'footer.php' ?>
</body>
</html>