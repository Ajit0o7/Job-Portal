<?php
$source = 'img/logo.png';
$destination = 'img/favicon.png';

$img = imagecreatefrompng($source);
if (!$img) {
    die("Failed to load image");
}

// Auto-crop transparent borders
$cropped = imagecropauto($img, IMG_CROP_TRANSPARENT);

if ($cropped !== false) { // imagecropauto returns false on failure or if nothing to crop (though usually it returns the image if nothing to crop in some versions)
    // Make sure transparency is saved
    imagesavealpha($cropped, true);
    imagepng($cropped, $destination);
    imagedestroy($cropped);
    echo "Cropped image saved to $destination\n";
} else {
    echo "Failed to crop image or no transparency to crop.\n";
}

imagedestroy($img);
?>
