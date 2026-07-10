<?php
    $servername = "localhost";  
    $username = "root";  
    $password = ""; 
    $dbname = "portal_db";  
    
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
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