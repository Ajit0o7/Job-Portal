<?php

    session_start();
    include '../database_configure.php';

    $delete = "DELETE FROM `employerlogin` WHERE employer_id=$_REQUEST[s_id]";
    $query = mysqli_query($conn,$delete);

    if($delete){
        ?><script>alert('Employer deleted succesfully.');location.replace('<?php echo BASE_URL; ?>/admin/viewemployerlist');</script><?php
    }

?>