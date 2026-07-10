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
                text: 'Please sign in to your employer account first to post a job.',
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
    <title>JobPortal - Post a Job</title>
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
                    <li><a href="listjob" class="nav-link active">List Out Job</a></li>
                    <?php if(isset($_SESSION['username'])): ?>
                        <li><a href="manage-jobs" class="nav-link">Update Job</a></li>
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
    <div style="padding-top: 140px; padding-bottom: 4rem; background: linear-gradient(135deg, rgba(246, 248, 253, 0.8) 0%, rgba(241, 246, 249, 0.8) 100%); min-height: 80vh;">
        
        <div style="text-align: center; margin-bottom: 3rem;">
            <span class="badge-pill" style="margin-bottom: 1rem; background: rgba(42, 157, 244, 0.1); color: var(--primary-color);"><i class="fas fa-bullhorn"></i> Hiring Now</span>
            <h2 style="font-size: 2.8rem; font-weight: 800; color: var(--text-main);">Post a <span class="text-gradient">New Job</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Fill out the details below to publish your job vacancy to thousands of active job seekers instantly.</p>
        </div>

        <div class="glass-card" style="max-width: 900px; margin: 0 auto; padding: 0; display: flex; flex-wrap: wrap; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-lg);">
            <!-- Image Side -->
            <div style="flex: 1; min-width: 300px; background: url('../img/jobportal-app-pic.webp') center/cover; position: relative;">
                <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(42, 157, 244, 0.8), rgba(17, 113, 186, 0.9)); padding: 3rem; color: white; display: flex; flex-direction: column; justify-content: center;">
                    <h3 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Reach Top Talent</h3>
                    <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem;">Provide clear, accurate details to attract the most qualified candidates for your position.</p>
                    <ul style="list-style: none; padding: 0; line-height: 2;">
                        <li><i class="fas fa-check-circle" style="color: #2ecc71; margin-right: 10px;"></i> Instant publishing</li>
                        <li><i class="fas fa-check-circle" style="color: #2ecc71; margin-right: 10px;"></i> AI-powered matching</li>
                        <li><i class="fas fa-check-circle" style="color: #2ecc71; margin-right: 10px;"></i> Detailed analytics</li>
                    </ul>
                </div>
            </div>

            <!-- Form Side -->
            <div style="flex: 2; min-width: 300px; padding: 3rem; background: white;">
                <form action="joblistform" method="post" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    
                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="title" style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">Job Title *</label>
                            <input type="text" id="title" name="title" required placeholder="e.g. Senior Software Engineer" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid var(--border-color); outline: none; transition: var(--transition);">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label for="location" style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">Location *</label>
                            <input type="text" id="location" name="location" required placeholder="e.g. Remote, New York" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid var(--border-color); outline: none; transition: var(--transition);">
                        </div>
                    </div>

                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="salary" style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">Salary *</label>
                            <input type="number" id="salary" name="salary" required placeholder="e.g. 80000" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid var(--border-color); outline: none; transition: var(--transition);">
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label for="experience" style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">Required Experience (Years) *</label>
                            <input type="number" id="experience" name="experience" required placeholder="e.g. 3" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid var(--border-color); outline: none; transition: var(--transition);">
                        </div>
                    </div>

                    <div>
                        <label for="skill" style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">Skills (Comma separated) *</label>
                        <textarea id="skill" name="skill" rows="3" required placeholder="e.g. PHP, JavaScript, MySQL" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid var(--border-color); outline: none; transition: var(--transition); resize: vertical;"></textarea>
                    </div>

                    <div>
                        <label for="requirements" style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">Other Requirements (Comma separated) *</label>
                        <textarea id="requirements" name="requirements" rows="3" required placeholder="e.g. Bachelor's Degree, Excellent Communication" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid var(--border-color); outline: none; transition: var(--transition); resize: vertical;"></textarea>
                    </div>

                    <div>
                        <label for="description" style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem; display: block;">Job Description *</label>
                        <textarea id="description" name="description" rows="5" required placeholder="Describe the responsibilities and day-to-day tasks..." style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid var(--border-color); outline: none; transition: var(--transition); resize: vertical;"></textarea>
                    </div>

                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn-primary rounded-pill w-100" style="padding: 14px; font-size: 1.1rem; border: none;"><i class="fas fa-paper-plane"></i> Publish Job Vacancy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
    <style>
        /* Input focus effects */
        input:focus, textarea:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(42, 157, 244, 0.1);
        }
    </style>
</body>
</html>