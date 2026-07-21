<?php 
session_start();

if(isset($_SESSION['username'])){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="icon" type="image/png" href="../img/favicon.png">
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
                window.location.replace('employerHome');
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
    <title>JobPortal Employer Hub - Sign Up</title>
    <style>
        body { background-color: var(--bg-alt); }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: row-reverse;
        }
        .auth-sidebar {
            flex: 1;
            background: linear-gradient(135deg, #1e293b, #0f172a);
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
            overflow-y: auto;
        }
        .auth-form {
            width: 100%;
            max-width: 450px;
        }
        .form-floating label { color: var(--text-muted); }
        .form-control:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 0.25rem rgba(15, 23, 42, 0.25);
        }
        .btn-employer {
            background-color: #0f172a;
            color: white;
            border: none;
        }
        .btn-employer:hover {
            background-color: #1e293b;
            color: white;
        }
    </style>
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-sidebar d-none d-lg-flex">
            <a href="../home-page"><img src="../img/logo.png" alt="JobPortal Logo" style="width: 200px; filter: brightness(0) invert(1); margin-bottom: 2rem;"></a>
            <h1 class="display-4 fw-bold mb-4">Grow Your Team</h1>
            <p class="lead mb-4">Create an employer account to post jobs, manage applications, and hire the best talent globally.</p>
        </div>
        
        <div class="auth-form-container">
            <div class="auth-form py-4">
                <div class="d-lg-none text-center mb-4">
                    <a href="../home-page"><img src="../img/logo.png" alt="JobPortal Logo" style="width: 150px;"></a>
                </div>
                <h2 class="h3 mb-4 fw-bold text-center">Create Employer Account</h2>
                
                <form method="POST" id="signup-form" onsubmit="event.preventDefault(); signupvalidate()">
                    <input type="number" name="forsignin" value="1" hidden>
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Company Name">
                        <label for="name">Company Name</label>
                        <div class="invalid-feedback" id="for_name" style="display:none;">Company name cannot be empty</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                        <label for="email">Email address</label>
                        <div class="invalid-feedback" id="for_email" style="display:none;">Email cannot be empty</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="location" name="location" placeholder="City, Country">
                                <label for="location">Location</label>
                                <div class="invalid-feedback" id="for_location" style="display:none;">Location cannot be empty</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="contact" name="contact" required pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Phone Number">
                                <label for="contact">Contact Number</label>
                                <div class="invalid-feedback" id="for_contact" style="display:none;">Contact number cannot be empty</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="psw" name="psw" placeholder="Password">
                        <label for="psw">Password</label>
                        <div class="invalid-feedback" id="for_password" style="display:none;">Password cannot be empty</div>
                        <div class="invalid-feedback" id="password_length" style="display:none;">Password must be at least 8 characters</div>
                    </div>
                    
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="pswcon" name="pswcon" placeholder="Confirm Password">
                        <label for="pswcon">Confirm Password</label>
                        <div class="invalid-feedback" id="for_con_password" style="display:none;">Please confirm your password</div>
                        <div class="invalid-feedback" id="password_match" style="display:none;">Passwords do not match</div>
                    </div>
                    
                    <button class="btn btn-employer w-100 py-3 mb-3 fw-bold rounded-3" type="submit" name="sign-up">Sign Up</button>
                    
                    <p class="text-center text-muted">Already have an employer account? <a href="signin-page" class="text-decoration-none fw-bold" style="color: #0f172a;">Sign In Here!</a></p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function signupvalidate() {
            var userName = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var locationVal = document.getElementById('location').value.trim();
            var contact = document.getElementById('contact').value.trim();
            var password = document.getElementById('psw').value.trim();
            var confirmPassword = document.getElementById('pswcon').value.trim();
            var isValidate = true;

            // Reset UI
            const fields = ['name', 'email', 'location', 'contact', 'psw', 'pswcon'];
            const errors = ['for_name', 'for_email', 'for_location', 'for_contact', 'for_password', 'password_length', 'for_con_password', 'password_match'];
            fields.forEach(id => document.getElementById(id).classList.remove('is-invalid'));
            errors.forEach(id => document.getElementById(id).style.display = 'none');

            if(userName === ''){
                document.getElementById('name').classList.add('is-invalid');
                document.getElementById('for_name').style.display = 'block';
                isValidate = false; 
            }
            if(email === ''){
                document.getElementById('email').classList.add('is-invalid');
                document.getElementById('for_email').style.display = 'block';
                isValidate = false; 
            }
            if(locationVal === ''){
                document.getElementById('location').classList.add('is-invalid');
                document.getElementById('for_location').style.display = 'block';
                isValidate = false; 
            }
            if(contact === ''){
                document.getElementById('contact').classList.add('is-invalid');
                document.getElementById('for_contact').style.display = 'block';
                isValidate = false; 
            }
            if(password === ''){
                document.getElementById('psw').classList.add('is-invalid');
                document.getElementById('for_password').style.display = 'block';
                isValidate = false;
            } else if(password.length < 8){
                document.getElementById('psw').classList.add('is-invalid');
                document.getElementById('password_length').style.display = 'block';
                isValidate = false;
            }

            if(confirmPassword === ''){
                document.getElementById('pswcon').classList.add('is-invalid');
                document.getElementById('for_con_password').style.display = 'block';
                isValidate = false;
            } else if(password !== confirmPassword){
                document.getElementById('pswcon').classList.add('is-invalid');
                document.getElementById('password_match').style.display = 'block';
                isValidate = false;
            }
            
            if(isValidate){
                document.getElementById("signup-form").submit();
            }
        }
    </script>

    <?php
    if ($_POST) {
        if ($_POST['forsignin'] == 1) {
            $full_name = $_REQUEST['name'];
            $email = $_REQUEST['email'];
            $password = $_REQUEST['psw'];
            $enc_password = password_hash($password, PASSWORD_DEFAULT);
            $location = $_REQUEST['location'];
            $contact = $_REQUEST['contact'];

            // Check if the email already exists
            $checkuser = "SELECT * FROM `employerlogin` WHERE Email_Address_E = '$email'";
            $check = mysqli_query($conn, $checkuser);

            // If the email exists, show error message
            if (mysqli_num_rows($check) > 0) {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Email already exists',
                        text: 'This email is already registered. Try signing in instead.',
                        confirmButtonColor: '#0f172a'
                    });
                </script>
                <?php
            } else {
                $sql = "INSERT INTO `employerlogin`(`Fullname_E`, `Email_Address_E`, `Password_E`, `Location`, `Contact`) VALUES ('$full_name', '$email', '$enc_password', '$location', '$contact')";
                if ($conn->query($sql) === TRUE) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Employer Account Created!',
                            text: 'You will be redirected to the sign-in page.',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            location.replace("<?php echo BASE_URL; ?>/employer/signin-page");
                        });
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Database Error',
                            text: '<?php echo addslashes($conn->error); ?>',
                            confirmButtonColor: '#0f172a'
                        });
                    </script>
                    <?php
                }
            }
        }
    }
    ?>
</body>
</html>
