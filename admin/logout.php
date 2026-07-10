<?php
    if(isset($_POST['logout'])){
        session_start();
        session_destroy();
        echo "<script>window.location.replace('index.php');</script>";
        exit;
    }
?>
<div class="navigation">
    <div class="nav-brand">
        <a href="dash"><img src="../img/logo.png" alt="logo" class="logo"></a>
        <span class="nav-badge">Admin Portal</span>
    </div>
    <div class="nav-user-actions">
        <span class="nav-username"><i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['Adname'] ?? 'Admin'); ?></span>
        <form method="POST" style="display: inline;">
            <button type="submit" name="logout" class="abutton">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </div>
</div>