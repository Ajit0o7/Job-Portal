<?php 
    session_start();
    include '../database_configure.php';
    if(!isset($_SESSION['username'])){
        ?><script type='text/javascript'>alert('Please sign in first'); location.replace("<?php echo BASE_URL; ?>/jobseeker/jobseekerHome");</script><?php
        exit;
    }
    $aid = intval($_SESSION['username']);
?>
<?php 

    $month = date('m');
    $day = date('d');
    $year = date('Y');

    $today = $year . '-' . $month . '-' . $day;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="jobseeker-style.css?v=<?php echo time(); ?>"/>
<link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>JobPortal Seekers Hub</title>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<style>
    /* CSS has been fully migrated to global main-styles.css */
    form input:focus, form textarea:focus { border-color: var(--primary-color) !important; box-shadow: 0 0 0 3px var(--primary-light) !important; }
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
        <!-- Modern Header Section -->
        <section class="banner" style="padding-top: 140px; min-height: 40vh; padding-bottom: 4rem; justify-content: center; text-align: center;">
            <div class="banner-content" style="flex: none; padding-right: 0;">
                <span class="badge-pill" style="margin-bottom: 1rem;">📝 Edit Mode</span>
                <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem;">Update Your <span class="text-gradient">Resume</span></h1>
                <p style="font-size: 1.2rem; color: var(--text-muted); max-width: 600px; margin: 0 auto;">Keep your professional profile fresh to catch the eye of top employers.</p>
            </div>
        </section>
        
        <?php
        include '../database_configure.php';
        $id = isset($_GET['sk_id']) ? intval($_GET['sk_id']) : 0;
        if ($id !== $aid) {
            ?><script type='text/javascript'>alert('Unauthorized access'); location.replace("<?php echo BASE_URL; ?>/jobseeker/jobseekerHome");</script><?php
            exit;
        }
        $select = "SELECT `sk_id`, `FullName`, `EmailAddress`, `Contact`, `Country`, `Provience`, `City`, `Address`, `pdffile`, `image`, `Seeker_id`, `Education`, `Workexp`, `skill`, `description` FROM `seekerresume` WHERE Seeker_id = $id";
        $query = mysqli_query($conn,$select);

        while ($result = mysqli_fetch_assoc($query)) {
            $name= $result['FullName'];
            $Semail = $result['EmailAddress'];
            $Scontact = $result['Contact'];

            $Scountry = $result['Country'];
            $Sproviend  = $result['Provience'];
            $district = $result['City'];
            $address = $result['Address'];

            $education = $result['Education'];
            $experience = $result['Workexp'];
            $skill = $result['skill'];
            $description = $result['description'] ?? '';
            $existing_image = $result['image'];

        ?>
        
        <div class="habt_container" style="max-width: 800px; padding-bottom: 5rem;">
            <div class="glass-card" style="padding: 3rem;">
                <h2 style="font-size: 2rem; font-weight: 700; color: var(--text-main); margin-bottom: 2rem; text-align: center;">Update Details</h2>
                
                <form id="propinfo-form" method="POST" enctype="multipart/form-data" action="updateresumeform?sk_id=<?php echo $id ;?>" style="display: flex; flex-direction: column; gap: 2.5rem;">
                    
                    <!-- Profile Picture Section -->
                    <div style="background: rgba(255, 255, 255, 0.4); padding: 2rem; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.5); display: flex; gap: 2.5rem; align-items: center;">
                        <div style="position: relative; width: 130px; height: 130px;">
                            <?php if (!empty($existing_image)): ?>
                                <img id="showimg" src="<?php echo htmlspecialchars($existing_image); ?>?v=<?php echo time(); ?>" alt="Profile Picture" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
                            <?php else: ?>
                                <img id="showimg" src="https://via.placeholder.com/130" alt="Profile Picture" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
                            <?php endif; ?>
                            <label for="file" style="position: absolute; bottom: 0; right: 0; background: var(--primary-color); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: var(--transition);">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="file" name="file" accept="image/*" onchange="document.getElementById('showimg').src=window.URL.createObjectURL(this.files[0]);" style="display: none;" /> 
                        </div>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1.3rem; color: var(--text-main); font-weight: 700; margin-bottom: 0.5rem;">Profile Picture</h4>
                            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0;">Upload a professional photo. Click the camera icon on the image to select a new file.</p>
                        </div>
                    </div>

                    <!-- Personal Details Section -->
                    <div>
                        <h4 style="font-size: 1.2rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1.5rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-user-circle"></i> Personal Details</h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-id-card text-muted"></i> Full Name:</label>
                                <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($name);?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition);">
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-envelope text-muted"></i> E-mail Address:</label>
                                <input type="email" name="email_s" id="email_s" value="<?php echo htmlspecialchars($Semail);?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition);">
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-phone-alt text-muted"></i> Contact Number:</label>
                                <input type="tel" name="contact_s" id="contact_s" value="<?php echo htmlspecialchars($Scontact);?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition);">
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-globe text-muted"></i> Country:</label>
                                <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($Scountry);?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition);">
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-map-marker-alt text-muted"></i> Province:</label>
                                <input type="text" name="provience" id="provience" value="<?php echo htmlspecialchars($Sproviend);?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition);">
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-city text-muted"></i> District / City:</label>
                                <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($district);?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition);">
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: span 2;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-home text-muted"></i> Detailed Address:</label>
                                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($address);?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition);">
                            </div>
                        </div>
                    </div>

                    <!-- Professional Profile Section -->
                    <div>
                        <h4 style="font-size: 1.2rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1.5rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-briefcase"></i> Professional Profile</h4>
                        
                        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-user-edit text-muted"></i> Professional Summary:</label>
                                <textarea id="description" name="description" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition); resize: vertical; min-height: 100px;"><?php echo htmlspecialchars($description);?></textarea>
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-graduation-cap text-muted"></i> Education (One per line):</label>
                                <textarea id="education" name="education" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition); resize: vertical; min-height: 100px;"><?php echo htmlspecialchars($education);?></textarea>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-lightbulb text-muted"></i> Skills (One per line):</label>
                                <textarea id="skill" name="skill" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition); resize: vertical; min-height: 100px;"><?php echo htmlspecialchars($skill);?></textarea>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <label style="font-weight: 600; color: var(--text-main);"><i class="fas fa-history text-muted"></i> Work Experience:</label>
                                <textarea id="work" name="work" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.7); font-size: 1rem; outline: none; transition: var(--transition); resize: vertical; min-height: 100px;"><?php echo htmlspecialchars($experience);?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div style="background: rgba(255, 255, 255, 0.4); padding: 2rem; border-radius: 16px; border: 1px dashed var(--primary-color); text-align: center;">
                        <i class="fas fa-file-pdf" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                        <h4 style="font-size: 1.2rem; color: var(--text-main); font-weight: 700; margin-bottom: 0.5rem;">Upload Updated Resume (PDF)</h4>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">Ensure your document is in PDF format for best compatibility.</p>
                        <input name="userfile" id="userfile" type="file" accept="application/pdf" style="max-width: 300px; margin: 0 auto; padding: 10px; border-radius: 12px; border: 1px solid var(--border-color); background: white; font-size: 0.9rem;"/>
                    </div>

                    <div class="banner-buttons" style="margin-top: 1rem; justify-content: center;">
                        <button type="submit" class="btn-primary rounded-pill w-100" style="padding: 16px; width: 100%; font-size: 1.2rem;"><i class="fas fa-save"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

                <?php } ?>
                <?php include "footer.php" ; ?>
    </body>
</html>