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
    <?php if (isset($GLOBALS['db_connect_error'])): ?>
        <div style="background: #fef2f2; border-bottom: 1px solid #fee2e2; color: #991b1b; padding: 12px; text-align: center; font-size: 0.95rem; font-weight: 500; font-family: 'Inter', sans-serif; position: relative; z-index: 10001;">
            <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
            Database offline: <code><?php echo htmlspecialchars($GLOBALS['db_connect_error']); ?></code>. Please import <code>portal_db.sql</code> and ensure MySQL is running.
        </div>
        <style>
            .nav-container { top: 68px !important; }
            @media (max-width: 940px) {
                .nav-content { top: 138px !important; }
            }
        </style>
    <?php endif; ?>

    <!-- Modern Floating Navigation -->
    <div class="nav-container">
        <nav class="glass-nav">
            <div class="logo"> 
                <a href="<?php echo $base_url; ?>home-page.php"><img src="<?php echo $base_url; ?>img/site-logo.png" alt="JobPortal Logo"></a>
            </div>
            
            <input type="checkbox" id="menu">
            <label for="menu" class="menu-button">
                <i class="fas fa-bars"></i>
            </label>
            
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="<?php echo $base_url; ?>home-page.php" class="nav-link <?php echo ($current_page == 'home-page.php') ? 'active' : ''; ?>">Home</a></li>      
                    <li><a href="<?php echo $base_url; ?>about.php" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About Us</a></li>
                    <li><a href="<?php echo $base_url; ?>contact.php" class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
                </ul>
                
                <div class="nav-actions">
                    <a href="<?php echo $base_url; ?>jobseeker/jobseekerHome.php" class="btn btn-outline-primary rounded-pill px-4 me-2 fw-semibold js-btn">Job Seekers</a>
                    <a href="<?php echo $base_url; ?>employer/employerHome.php" class="btn btn-primary rounded-pill px-4 fw-semibold emp-btn">Employers</a>
                </div>
            </div>
        </nav>
    </div>
