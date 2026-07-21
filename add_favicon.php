<?php
$dirs = [
    './' => 'img/logo.png',
    './jobseeker/' => '../img/logo.png',
    './employer/' => '../img/logo.png',
    './admin/' => '../img/logo.png'
];

$added = 0;

foreach ($dirs as $dir => $faviconPath) {
    if (!is_dir($dir)) continue;
    
    $files = glob($dir . "*.php");
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Skip if already has an icon link or doesn't have a head tag
        if (strpos($content, 'rel="icon"') !== false || strpos($content, '</head>') === false) {
            continue;
        }
        
        $faviconTag = '    <link rel="icon" type="image/png" href="' . $faviconPath . '">';
        $newContent = str_replace('</head>', $faviconTag . "\n</head>", $content);
        
        file_put_contents($file, $newContent);
        $added++;
    }
}

echo "Added favicon to $added files.\n";
?>
