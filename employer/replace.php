<?php
$files = glob("*.php");
foreach($files as $file) {
    if($file == "replace.php") continue;
    $content = file_get_contents($file);
    
    $content = str_replace("viewjob.php?j_id=<?php echo \$_SESSION['username']; ?>", "manage-jobs", $content);
    $content = str_replace("viewjob.php?j_id=<?php echo \$eid12; ?>", "manage-jobs", $content);
    $content = str_replace("viewjob.php?j_id=<?= \$id; ?>", "manage-jobs", $content);
    $content = str_replace("viewjob.php?j_id=<?php echo \$id; ?>", "manage-jobs", $content);

    file_put_contents($file, $content);
}
echo "Done";
