<?php 
    session_start();
    include '../database_configure.php';
    
    $unathorized = "";
    if($_POST){
        $username = $_REQUEST['Adname'];
        $password = $_REQUEST['Adpassword'];
        $query = mysqli_query($conn,"SELECT * FROM `admin` WHERE `A_name` = '$username' && `password` = '$password'") ;
       
        $row = mysqli_fetch_array($query);
		
        if(is_array($row)){
            $_SESSION['Adname'] = $row['A_name'];
            $_SESSION['Adpassword'] = $row['password'];
        }else{
            $unathorized = "Invalid admin username or password." ;
        }
        if(isset($_SESSION["Adname"])){
            header('location:dash.php');
            exit;
        }
    }
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobPortal: Admin Login</title>
    
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="adminstyle.css?v=<?php echo time(); ?>" />
    
    <script>
        function Showpassword() {
            var x = document.getElementById("Adpassword");
            var eye = document.getElementById("passeye");
            if (x.type === "password") {
                x.type = "text";
                eye.classList.remove("fa-eye");
                eye.classList.add("fa-eye-slash");
            } else {
                x.type = "password";
                eye.classList.remove("fa-eye-slash");
                eye.classList.add("fa-eye");
            }
        }
    </script>
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
<body class="login-body">
    <div class="login-container">
        <!-- Image Section -->
        <div class="login-image-section">
            <img src="../img/job-hiring-vacancy-team-interview-career-recruiting.jpg" alt="Admin Portal" />
            <div class="login-image-overlay">
                <h2>JobPortal Management</h2>
                <p>Access the control center to manage listings, view registered job seekers and employers, and analyze market trends.</p>
            </div>
        </div>
        
        <!-- Form Section -->
        <div class="login-form-section">
            <div class="login-header">
                <img src="../img/logo.png" alt="JobPortal Logo" />
                <h3>Welcome Back</h3>
                <p>Please enter your admin credentials</p>
            </div>
            
            <?php if (!empty($unathorized)): ?>
                <div class="error-alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php echo htmlspecialchars($unathorized); ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="input-group">
                    <input type="text" id="Adname" name="Adname" placeholder="Admin Username" required autocomplete="username" />
                    <i class="fas fa-user"></i>
                </div>
                
                <div class="input-group">
                    <input type="password" id="Adpassword" name="Adpassword" placeholder="Password" required autocomplete="current-password" />
                    <i class="fas fa-lock"></i>
                    <i class="fas fa-eye toggle-password" id="passeye" onclick="Showpassword();"></i>
                </div>
                
                <button type="submit" class="login-btn">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>


