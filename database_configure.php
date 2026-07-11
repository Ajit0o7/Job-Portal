<?php
    $servername = "localhost";  
    $username = "root";  
    $password = ""; 
    $dbname = "portal_db";  
    
    // Turn off exception throwing for mysqli so it fails gracefully without PHP fatal crashes
    @mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        $GLOBALS['db_connect_error'] = mysqli_connect_error();
    }
    
    // Dynamic Base URL calculation
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $dir = str_replace('\\', '/', __DIR__);
    $relative_path = str_replace($doc_root, '', $dir);
    
    // Prevent redefinition if included multiple times
    if (!defined('BASE_URL')) {
        define('BASE_URL', $protocol . "://" . $host . $relative_path);
    }
?>