<?php
$directory = new RecursiveDirectoryIterator('c:/wamp64/www/Job-Portal-backup-1/Job-Portal');
$iterator = new RecursiveIteratorIterator($directory);

$count = 0;
foreach ($iterator as $info) {
    if ($info->isFile() && $info->getExtension() === 'php') {
        $content = file_get_contents($info->getPathname());
        if (strpos($content, BASE_URL . '/') !== false) {
            $new_content = str_replace(BASE_URL . '/', BASE_URL . '/', $content);
            file_put_contents($info->getPathname(), $new_content);
            echo "Updated: " . $info->getPathname() . "\n";
            $count++;
        }
    }
}
echo "Replaced in $count files.\n";
