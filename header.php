<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$doc_root = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
$dir = str_replace('\\', '/', __DIR__);
$base_path = str_ireplace($doc_root, '', $dir);
$base_url = $protocol . $host . $base_path . '/';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>main-styles.css?v=<?php echo time(); ?>"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>JobPortal</title>
</head>
<body>
    <!-- Modern Floating Navigation -->
    <div class="nav-container">
        <nav class="glass-nav">
            <div class="logo"> 
                <a href="<?php echo $base_url; ?>home-page"><img src="<?php echo $base_url; ?>img/site-logo.png" alt="JobPortal Logo"></a>
            </div>
            
            <input type="checkbox" id="menu">
            <label for="menu" class="menu-button">
                <i class="fas fa-bars"></i>
            </label>
            
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="<?php echo $base_url; ?>home-page" class="nav-link <?php echo ($current_page == 'home-page.php') ? 'active' : ''; ?>">Home</a></li>      
                    <li><a href="<?php echo $base_url; ?>about" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About Us</a></li>
                    <li><a href="<?php echo $base_url; ?>contact" class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
                </ul>
                
                <div class="nav-actions">
                    <a href="<?php echo $base_url; ?>jobseeker/jobseekerHome" class="btn btn-outline-primary rounded-pill px-4 me-2 fw-semibold js-btn">Job Seekers</a>
                    <a href="<?php echo $base_url; ?>employer/employerHome" class="btn btn-primary rounded-pill px-4 fw-semibold emp-btn">Employers</a>
                </div>
            </div>
        </nav>
    </div>
