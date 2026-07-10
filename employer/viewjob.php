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
                text: 'Please sign in to your employer account first to view your jobs.',
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
    <base href="<?php echo BASE_URL; ?>/employer/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>JobPortal - Manage Jobs</title>
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh; background: var(--bg-alt); margin: 0;">
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
                        <li><a href="viewjob?j_id=<?php echo $_SESSION['username']; ?>" class="nav-link active">Update Job</a></li>
                        <li><a href="checkapplication" class="nav-link">Listed Jobs</a></li>
                    <?php endif; ?>
                </ul>
                
                <div class="nav-actions">
                    <a href="../logout" class="btn btn-outline-danger rounded-pill px-4 fw-semibold js-btn">Log out</a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Header Section -->
    <div style="flex: 1; padding-top: 140px; padding-bottom: 4rem; background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%);">
        
        <div style="text-align: center; margin-bottom: 4rem;">
            <span class="badge-pill" style="margin-bottom: 1rem; background: rgba(42, 157, 244, 0.1); color: var(--primary-color);"><i class="fas fa-briefcase"></i> Active Listings</span>
            <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--text-main);">Manage <span class="text-gradient">Your Jobs</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Update or remove your current job postings. Keeping your listings accurate helps attract the best candidates.</p>
        </div>

        <div style="max-width: 1400px; margin: 0 auto; padding: 0 5%;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2.5rem;">
                <?php
                $select = "SELECT * FROM `job_postings` WHERE employer_id = $eid12 AND status = 'open' ORDER BY job_id DESC";
                $runquery = mysqli_query($conn, $select);

                if (mysqli_num_rows($runquery) > 0) {
                    while ($result = mysqli_fetch_assoc($runquery)) {
                        $title = $result['title'];
                        $datePosted = $result['date_posted'];
                        $jobid = $result['job_id'];
                        $location = $result['location'];
                        ?>
                        <div class="glass-card" style="padding: 2.5rem; display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--primary-color); transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-md)';">
                            <div>
                                <span class="badge-pill" style="font-size: 0.85rem; background: rgba(46, 204, 113, 0.1); color: #2ecc71; margin-bottom: 1.5rem; display: inline-block;"><i class="fas fa-clock"></i> Posted: <?php echo date('M d, Y', strtotime($datePosted)); ?></span>
                                <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem; line-height: 1.3;"><?php echo htmlspecialchars($title); ?></h3>
                                <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 1.5rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-light);"></i> <?php echo htmlspecialchars($location); ?></p>
                            </div>
                            
                            <div style="display: flex; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 1.5rem; margin-top: auto;">
                                <a href="edit-job/<?php echo $jobid;?>" style="text-decoration: none; flex: 1;">
                                    <button class="btn btn-primary rounded-pill w-100" style="padding: 10px; font-size: 0.95rem;"><i class="fas fa-edit"></i> Edit</button>
                                </a>
                                <a href="#" onclick="confirmDelete(<?php echo $jobid;?>); return false;" style="text-decoration: none; flex: 1;">
                                    <button class="btn rounded-pill w-100" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.2); padding: 10px; font-size: 0.95rem; transition: background 0.2s;" onmouseover="this.style.background='#e74c3c'; this.style.color='white';" onmouseout="this.style.background='rgba(231, 76, 60, 0.1)'; this.style.color='#e74c3c';"><i class="fas fa-trash-alt"></i> Remove</button>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div style='grid-column: 1 / -1; text-align: center; padding: 4rem; background: rgba(255,255,255,0.6); border-radius: 20px; border: 1px dashed var(--border-color);'>";
                    echo "<i class='fas fa-folder-open' style='font-size: 4rem; color: var(--border-color); margin-bottom: 1rem;'></i>";
                    echo "<h3 style='color: var(--text-main); font-weight: 700;'>No Active Jobs</h3>";
                    echo "<p style='color: var(--text-muted); margin-bottom: 2rem;'>You haven't posted any job vacancies yet.</p>";
                    echo "<a href='listjob' class='btn btn-primary rounded-pill' style='padding: 12px 30px;'><i class='fas fa-plus'></i> Post Your First Job</a>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php' ?>

    <script>
        function confirmDelete(jobId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#bdc3c7',
                confirmButtonText: '<i class="fas fa-trash"></i> Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete-job/' + jobId;
                }
            })
        }
    </script>
</body>
</html>