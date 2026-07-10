<?php
    include 'session.php';
    session_destroy();
    header("location:" . BASE_URL . "/home-page.php");
    exit;
?>