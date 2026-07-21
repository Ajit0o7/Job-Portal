<?php
    session_start();
    include '../database_configure.php';
    
    if(!isset($_SESSION['username'])) {
        header("Location: employerHome.php");
        exit;
    }
    
    $eid12 = $_SESSION['username'];
    $jid = isset($_GET['j_id']) ? intval($_GET['j_id']) : 0;

    // Secure delete: Only close if it belongs to this employer
    $delete = "UPDATE `job_postings` SET `status`='close' WHERE job_id = $jid AND employer_id = $eid12";
    $query = mysqli_query($conn, $delete);

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="icon" type="image/png" href="../img/favicon.png">
</head>
    <body style="background: #f4f7fb;">
        <?php if($query && mysqli_affected_rows($conn) > 0): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'The job vacancy has been removed.',
                    confirmButtonColor: '#2ecc71',
                    allowOutsideClick: false
                }).then(() => {
                    window.location.replace('<?php echo BASE_URL; ?>/employer/manage-jobs');
                });
            </script>
        <?php else: ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not remove the job vacancy.',
                    confirmButtonColor: '#e74c3c'
                }).then(() => {
                    window.location.replace('<?php echo BASE_URL; ?>/employer/manage-jobs');
                });
            </script>
        <?php endif; ?>
    </body>
    </html>