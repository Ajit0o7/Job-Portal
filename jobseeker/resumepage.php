<?php 
    session_start();
    include '../database_configure.php';
    if(!isset($_SESSION['username'])){
        ?><script type='text/javascript'>alert('Please sign in first'); location.replace("<?php echo BASE_URL; ?>/jobseeker/signin-page");</script><?php
        }
        $aid = $_SESSION['username'];
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resumeForm input, #resumeForm textarea').on('input', function() {

                $('#previewFullName').text($('#FullName').val() || 'John Doe');
                $('#previewEmail').html('<i class="fas fa-envelope" style="color: var(--primary-color); width: 20px;"></i> ' + ($('#EmailAddress').val() || 'john.doe@example.com'));
                $('#previewContact').html('<i class="fas fa-phone-alt" style="color: var(--primary-color); width: 20px;"></i> ' + ($('#Contact').val() || '+123456789'));
                
                $('#previewAddress').text($('#Address').val() || '123 Main St, Apt 4B');
                $('#previewLocation').text($('#City').val() + ', ' + $('#Provience').val() + ', ' + $('#Country').val());
                
                $('#previewEducation').html($('#Education').val().split('\n').filter(i => i.trim() !== '').map(item => `<li><i class="fas fa-check" style="color: var(--primary-light); margin-right: 5px;"></i> ${item}</li>`).join(''));
                
                $('#previewWorkexp').html($('#Workexp').val().split('\n').filter(i => i.trim() !== '').map(item => `<li><i class="fas fa-check" style="color: var(--primary-light); margin-right: 5px;"></i> ${item} Years</li>`).join(''));
                
                $('#previewSkills').html($('#skill').val().split('\n').filter(i => i.trim() !== '').map(item => `<li class="badge-pill" style="margin-bottom: 0; font-size: 0.85rem; padding: 0.3rem 0.8rem;">${item}</li>`).join(''));
            });

            $('#image').on('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profilePicture').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#pdffile').on('change', function(event) {
                if(event.target.files[0]) {
                    $('#downloadResume').attr('href', URL.createObjectURL(event.target.files[0]));
                }
            });
        });
    </script>

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
                <span class="badge-pill" style="margin-bottom: 1rem;">📄 Your Professional Story</span>
                <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1rem;">Craft Your <span class="text-gradient">Best Resume</span></h1>
                <p style="font-size: 1.2rem; color: var(--text-muted); max-width: 600px; margin: 0 auto;">A stunning resume is the first step towards landing your dream job.</p>
            </div>
        </section>

        <?php 
                $resumecheck = "SELECT * FROM `seekerresume` WHERE Seeker_id= $aid";
                $runquery = mysqli_query($conn,$resumecheck);

                if(mysqli_num_rows($runquery)<=0){
            ?>
        <div class="habt_container" style="max-width: 1400px; padding-bottom: 5rem; align-items: flex-start; gap: 3rem;">
            <!-- Form Column -->
            <div class="glass-card" style="flex: 1; padding: 2.5rem;">
                <h2 style="font-size: 2rem; font-weight: 700; color: var(--text-main); margin-bottom: 2rem; text-align: center;">Resume Builder</h2>
                <?php
                    $select = "SELECT `Fullname_S`, `Email_S` FROM `seekerlogin` WHERE Seeker_id = $aid";
                    $query = mysqli_query($conn,$select);

                    while ($result = mysqli_fetch_assoc($query)) {
                        $name= $result['Fullname_S'];
                        $email = $result['Email_S'];
                ?>
                <form id="resumeForm" action="resumeform" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1.2rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="FullName" style="font-weight: 600; color: var(--text-main);">Full Name:</label>
                        <input type="text" name="fullname" id="FullName" value="<?php echo $name;?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition);">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="EmailAddress" style="font-weight: 600; color: var(--text-main);">Email Address:</label>
                        <input type="email" name="email_s" id="EmailAddress" value="<?php echo $email;?>" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition);">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="Contact" style="font-weight: 600; color: var(--text-main);">Contact Number:</label>
                        <input type="tel" required pattern="[0-9]*" placeholder="Digits only" oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="contact_s" id="Contact" style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition);">
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                            <label for="City" style="font-weight: 600; color: var(--text-main);">City:</label>
                            <input type="text" name="city" id="City" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition);">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                            <label for="Provience" style="font-weight: 600; color: var(--text-main);">Province:</label>
                            <input type="text" name="provience" id="Provience" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition);">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                            <label for="Country" style="font-weight: 600; color: var(--text-main);">Country:</label>
                            <input type="text" name="country" id="Country" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition);">
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="Address" style="font-weight: 600; color: var(--text-main);">Detailed Address:</label>
                        <textarea name="address" id="Address" rows="2" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition); resize: vertical;"></textarea>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                            <label for="pdffile" style="font-weight: 600; color: var(--text-main);">Resume (PDF):</label>
                            <input type="file" name="userfile" id="pdffile" accept=".pdf" required style="padding: 10px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 0.9rem;">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                            <label for="image" style="font-weight: 600; color: var(--text-main);">Profile Picture:</label>
                            <input type="file" name="file" id="image" accept="image/*" style="padding: 10px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 0.9rem;">
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="Education" style="font-weight: 600; color: var(--text-main);">Education (One per line):</label>
                        <textarea name="education" id="Education" rows="3" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition); resize: vertical;"></textarea>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="Workexp" style="font-weight: 600; color: var(--text-main);">Years of Work Exp:</label>
                        <input type="number" name="work" id="Workexp" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition);">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="skill" style="font-weight: 600; color: var(--text-main);">Skills (One per line):</label>
                        <textarea name="skill" id="skill" rows="3" required style="padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border-color); background: rgba(255,255,255,0.6); font-size: 1rem; outline: none; transition: var(--transition); resize: vertical;"></textarea>
                    </div>
                    
                    <div class="banner-buttons" style="margin-top: 1rem; justify-content: center;">
                        <button type="submit" class="btn-primary rounded-pill w-100" style="padding: 15px; width: 100%;">Submit Resume</button>
                    </div>
                </form>
                <?php } ?>
            </div>

            <!-- Resume Display Column -->
            <div class="glass-card" style="flex: 1.2; padding: 2.5rem; background: rgba(255,255,255,0.85); position: sticky; top: 120px;">
                <h2 style="font-size: 2rem; font-weight: 700; color: var(--text-main); margin-bottom: 2rem; text-align: center;">Live <span class="text-gradient">Preview</span></h2>
                
                <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                    <!-- Left Side Preview -->
                    <div style="flex: 1; text-align: center; border-right: 1px solid var(--border-color); padding-right: 2rem; min-width: 200px;">
                        <img id="profilePicture" src="https://via.placeholder.com/150" alt="Profile Picture" style="display:block; width: 140px; height: 140px; border-radius: 50%; object-fit: cover; margin: 0 auto 1.5rem auto; border: 4px solid var(--bg-alt); box-shadow: var(--shadow-md);">
                        <h3 id="previewFullName" style="font-size: 1.6rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;">John Doe</h3>
                        <p id="previewEmail" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.3rem;"><i class="fas fa-envelope" style="color: var(--primary-color); width: 20px;"></i> john.doe@example.com</p>
                        <p id="previewContact" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;"><i class="fas fa-phone-alt" style="color: var(--primary-color); width: 20px;"></i> +123456789</p>
                        
                        <div style="text-align: left;">
                            <h4 style="font-size: 1.1rem; color: var(--primary-color); font-weight: 700; margin-bottom: 0.8rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;">Address</h4>
                            <p id="previewAddress" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.2rem;">123 Main St, Apt 4B</p>
                            <p id="previewLocation" style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1rem;">New York, NY, USA</p>
                        </div>
                    </div>

                    <!-- Right Side Preview -->
                    <div style="flex: 1.5; min-width: 250px;">
                        <div style="margin-bottom: 2rem;">
                            <h4 style="font-size: 1.2rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-graduation-cap"></i> Education</h4>
                            <ul id="previewEducation" style="list-style: none; padding-left: 0; color: var(--text-main); font-size: 0.95rem; line-height: 1.6;">
                                <li><i class="fas fa-check" style="color: var(--primary-light); margin-right: 5px;"></i> Bachelor of Science in Computer Science</li>
                                <li><i class="fas fa-check" style="color: var(--primary-light); margin-right: 5px;"></i> Master of Science in Software Engineering</li>
                            </ul>
                        </div>
                        
                        <div style="margin-bottom: 2rem;">
                            <h4 style="font-size: 1.2rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-briefcase"></i> Work Experience</h4>
                            <ul id="previewWorkexp" style="list-style: none; padding-left: 0; color: var(--text-main); font-size: 0.95rem; line-height: 1.6;">
                                <li><i class="fas fa-check" style="color: var(--primary-light); margin-right: 5px;"></i> 2 Years</li>
                            </ul>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <h4 style="font-size: 1.2rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-lightbulb"></i> Skills</h4>
                            <ul id="previewSkills" style="list-style: none; padding-left: 0; display: flex; flex-wrap: wrap; gap: 8px;">
                                <li class="badge-pill" style="margin-bottom: 0; font-size: 0.85rem; padding: 0.3rem 0.8rem;">JavaScript</li>
                                <li class="badge-pill" style="margin-bottom: 0; font-size: 0.85rem; padding: 0.3rem 0.8rem;">PHP</li>
                            </ul>
                        </div>
                        
                        <div>
                            <div class="banner-buttons">
                                <a id="downloadResume" href="#" class="w-100" style="text-decoration: none;"><button type="button" class="btn-secondary rounded-pill w-100" style="width: 100%;"><i class="fas fa-file-pdf"></i> View Document</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php }else{
            $seeker_id = $aid;
            $sql = $seeker_id ? "SELECT * FROM seekerresume WHERE seeker_id = $seeker_id" : "SELECT * FROM seekerresume";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0):
                while($row = $result->fetch_assoc()):
            
        ?>
        <div style="text-align: center; margin-bottom: 3rem; padding-top: 2rem;">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);">Your <span class="text-gradient">Digital Dossier</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">This is how top employers see your professional profile.</p>
        </div>
        
        <div class="glass-card" style="max-width: 900px; margin: 0 auto 4rem auto; padding: 3rem; background: rgba(255,255,255,0.9);">
            <div style="display: flex; gap: 3rem; flex-wrap: wrap;">
                <!-- Left Column -->
                <div style="flex: 1; min-width: 300px; text-align: center; border-right: 1px solid var(--border-color); padding-right: 2rem;">
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>?v=<?php echo time(); ?>" alt="Profile Picture" style="width: 160px; height: 160px; border-radius: 50%; object-fit: cover; margin: 0 auto 1.5rem auto; border: 4px solid var(--bg-alt); box-shadow: var(--shadow-md);">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/160" alt="Profile Picture" style="width: 160px; height: 160px; border-radius: 50%; object-fit: cover; margin: 0 auto 1.5rem auto; border: 4px solid var(--bg-alt); box-shadow: var(--shadow-md);">
                    <?php endif; ?>
                    
                    <h3 style="font-size: 1.8rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;"><?php echo htmlspecialchars($row['FullName']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 0.3rem;"><i class="fas fa-envelope" style="color: var(--primary-color); width: 25px;"></i> <?php echo htmlspecialchars($row['EmailAddress']); ?></p>
                    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 1.5rem;"><i class="fas fa-phone-alt" style="color: var(--primary-color); width: 25px;"></i> <?php echo htmlspecialchars($row['Contact']); ?></p>
                    
                    <div style="text-align: left; margin-top: 2rem;">
                        <h4 style="font-size: 1.2rem; color: var(--primary-color); font-weight: 700; margin-bottom: 0.8rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;">Address</h4>
                        <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 0.2rem;"><?php echo htmlspecialchars($row['Address']); ?></p>
                        <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 1rem;"><?php echo htmlspecialchars($row['City']) . ', ' . htmlspecialchars($row['Provience']) . ', ' . htmlspecialchars($row['Country']); ?></p>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div style="flex: 1.5; min-width: 300px;">
                    <div style="margin-bottom: 2.5rem;">
                        <h4 style="font-size: 1.3rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1.2rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-graduation-cap"></i> Education</h4>
                        <ul style="list-style: none; padding-left: 0; color: var(--text-main); font-size: 1rem; line-height: 1.8;">
                            <?php
                            $educations = explode("\n", $row['Education']);
                            foreach ($educations as $education) {
                                if (trim($education) != '') {
                                    echo '<li><i class="fas fa-check" style="color: var(--primary-light); margin-right: 8px;"></i> ' . htmlspecialchars(trim($education)) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    
                    <div style="margin-bottom: 2.5rem;">
                        <h4 style="font-size: 1.3rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1.2rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-briefcase"></i> Work Experience</h4>
                        <ul style="list-style: none; padding-left: 0; color: var(--text-main); font-size: 1rem; line-height: 1.8;">
                            <?php
                            $experiences = explode("\n", $row['Workexp']);
                            foreach ($experiences as $experience) {
                                if (trim($experience) != '') {
                                    echo '<li><i class="fas fa-check" style="color: var(--primary-light); margin-right: 8px;"></i> ' . htmlspecialchars(trim($experience)) . ' Years</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <div style="margin-bottom: 2.5rem;">
                        <h4 style="font-size: 1.3rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1.2rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-lightbulb"></i> Skills</h4>
                        <ul style="list-style: none; padding-left: 0; display: flex; flex-wrap: wrap; gap: 10px;">
                            <?php
                            $skills = explode("\n", $row['skill']);
                            foreach ($skills as $skill) {
                                if (trim($skill) != '') {
                                    echo '<li class="badge-pill" style="margin-bottom: 0; font-size: 0.9rem; padding: 0.4rem 1rem;">' . htmlspecialchars(trim($skill)) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    
                    <div style="margin-top: 3rem;">
                        <h4 style="font-size: 1.3rem; color: var(--primary-color); font-weight: 700; margin-bottom: 1.2rem; border-bottom: 2px solid var(--primary-light); padding-bottom: 0.5rem;"><i class="fas fa-file-pdf"></i> Resume Document</h4>
                        <?php if (!empty($row['pdffile'])): ?>
                            <div class="banner-buttons">
                                <a href="<?php echo htmlspecialchars($row['pdffile']); ?>" target="_blank" style="text-decoration: none;"><button type="button" class="btn-secondary rounded-pill"><i class="fas fa-external-link-alt"></i> View Original PDF</button></a>
                            </div>
                        <?php else: ?>
                            <p style="color: #f39c12; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> No resume uploaded.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="banner-buttons" style="justify-content: center; margin-top: 4rem; border-top: 1px solid var(--border-color); padding-top: 2.5rem;">
                <a href="managecv?sk_id=<?php echo $_SESSION['username']; ?>" style="text-decoration: none;"><button type="button" class="btn-primary rounded-pill" style="padding: 15px 40px; font-size: 1.1rem;"><i class="fas fa-edit"></i> Update Digital Dossier</button></a>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
            <div class="habt_container" style="text-align: center; padding: 5rem 0;">
                <div class="glass-card" style="padding: 3rem;">
                    <i class="fas fa-file-alt" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1.5rem;"></i>
                    <h2 style="font-size: 2rem; color: var(--text-main); margin-bottom: 1rem;">No resumes found</h2>
                    <p style="color: var(--text-muted);">It looks like you haven't uploaded a resume yet.</p>
                </div>
            </div>
        <?php endif; } ?>
        <?php include "footer.php" ; ?>
    </body>
</html>