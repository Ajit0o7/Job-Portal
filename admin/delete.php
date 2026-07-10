<?php

    session_start();
    include '../database_configure.php';

    $delete = "DELETE FROM `job_postings` WHERE job_id=$_REQUEST[s_id]";
    $query = mysqli_query($conn,$delete);

    if($delete){
        ?><script>alert('Job list deleted succesfully.');location.replace('dash');</script><?php
    }

?>