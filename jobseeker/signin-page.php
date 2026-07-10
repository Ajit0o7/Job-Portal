<?php 
session_start();

if(isset($_SESSION['username'])){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body style="background: #f4f7fb;">
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Already Signed In',
                text: 'You are already logged into your account.',
                confirmButtonColor: '#1171ba',
                allowOutsideClick: false
            }).then(() => {
                window.location.replace('jobseekerHome');
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}

include '../database_configure.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main-styles.css?v=<?php echo time(); ?>"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>JobPortal Seekers Hub - Sign In</title>
    <style>
        body { background-color: var(--bg-alt); }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
        }
        .auth-sidebar {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
        }
        .auth-form-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-color);
            padding: 2rem;
        }
        .auth-form {
            width: 100%;
            max-width: 450px;
        }
        .form-floating label { color: var(--text-muted); }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-sidebar d-none d-lg-flex">
            <a href="../home-page"><img src="../img/logo.png" alt="JobPortal Logo" style="width: 200px; filter: brightness(0) invert(1); margin-bottom: 2rem;"></a>
            <h1 class="display-4 fw-bold mb-4">Welcome Back!</h1>
            <p class="lead mb-4">Sign in to access your dashboard, track applications, and find your dream job.</p>
        </div>
        
        <div class="auth-form-container">
            <div class="auth-form">
                <div class="d-lg-none text-center mb-4">
                    <a href="../home-page"><img src="../img/logo.png" alt="JobPortal Logo" style="width: 150px;"></a>
                </div>
                <h2 class="h3 mb-4 fw-bold text-center">Sign in to your account</h2>
                
                <form id="sign-in" method="POST" action="" onsubmit="event.preventDefault(); signin()">
                    <input type="number" name="forsignin" value="2" hidden>
                    
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="logemail" name="logemail" placeholder="name@example.com">
                        <label for="logemail">Email address</label>
                        <div class="invalid-feedback" id="for_log_email" style="display:none;">Email cannot be empty</div>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="logpsw" name="logpsw" placeholder="Password">
                        <label for="logpsw">Password</label>
                        <div class="invalid-feedback" id="for_log_password" style="display:none;">Password cannot be empty</div>
                    </div>
                    
                    <button class="btn btn-primary w-100 py-3 mb-3 fw-bold rounded-3" type="submit" name="sign-in" style="background-color: var(--primary-color); border: none;">Sign In</button>
                    
                    <p class="text-center text-muted">Don't have an account? <a href="signup-page" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Register Here!</a></p>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function signin() {
            var email = document.getElementById('logemail').value.trim();
            var password = document.getElementById('logpsw').value.trim();
            var isValidate = true;

            // Reset validation states
            document.getElementById('logemail').classList.remove('is-invalid');
            document.getElementById('logpsw').classList.remove('is-invalid');
            document.getElementById('for_log_email').style.display = 'none';
            document.getElementById('for_log_password').style.display = 'none';

            if (email === '') {
                document.getElementById('logemail').classList.add('is-invalid');
                document.getElementById('for_log_email').style.display = 'block';
                isValidate = false; 
            }

            if (password === '') {
                document.getElementById('logpsw').classList.add('is-invalid');
                document.getElementById('for_log_password').style.display = 'block';
                isValidate = false;
            }

            if (isValidate) {
                document.getElementById("sign-in").submit();
            }
        }
    </script>

    <?php
    if($_POST){
        if($_POST['forsignin']==2){
            $email = $_REQUEST['logemail'];
            $password = $_REQUEST['logpsw'];
            $query = ("SELECT * FROM `seekerlogin` WHERE Email_S = '$email'") ;
            $querycheck = mysqli_query($conn,$query);
            $fetch = mysqli_num_rows($querycheck);
            
            if($fetch!=0){
                $email_check = mysqli_fetch_assoc($querycheck);
                $db_pass = $email_check['Password_S'];
                $pass_decode = password_verify($password, $db_pass);
        
                if($pass_decode){
                    $_SESSION['username'] = $email_check['Seeker_id'];
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sign in successful',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.replace("<?php echo BASE_URL; ?>/jobseeker/jobseekerHome");
                        });
                    </script>
                    <?php
                }else{
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Password',
                            text: 'Please check your password and try again.',
                            confirmButtonColor: '#2563eb'
                        }).then(() => {
                            location.replace("<?php echo BASE_URL; ?>/jobseeker/signin-page");
                        });
                    </script>
                    <?php
                }
            }else{
                ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Email not registered',
                        text: 'Please register first before signing in.',
                        confirmButtonColor: '#2563eb'
                    }).then(() => {
                        location.replace("<?php echo BASE_URL; ?>/jobseeker/signup-page");
                    });
                </script>
                <?php
            }
        }
    }
    mysqli_close($conn);
    ?> 
</body>
</html>
