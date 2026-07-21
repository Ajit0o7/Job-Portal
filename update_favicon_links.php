<?php
$dirs = [
    './',
    './jobseeker/',
    './employer/',
    './admin/'
];

$updated = 0;

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    
    $files = glob($dir . "*.php");
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Find if logo.png is used as favicon
        if (strpos($content, '<link rel="icon" type="image/png" href="img/favicon.png">') !== false ||
            strpos($content, '<link rel="icon" type="image/png" href="../img/favicon.png">') !== false) {
            
            $newContent = str_replace(
                '<link rel="icon" type="image/png" href="img/favicon.png">',
                '<link rel="icon" type="image/png" href="img/favicon.png">',
                $content
            );
            $newContent = str_replace(
                '<link rel="icon" type="image/png" href="../img/favicon.png">',
                '<link rel="icon" type="image/png" href="../img/favicon.png">',
                $newContent
            );
            
            file_put_contents($file, $newContent);
            $updated++;
        }
    }
}

echo "Updated favicon links in $updated files.\n";
?>
