<?php
// Create a 200 x 200 image
$canvas = imagecreatetruecolor(200, 200);

// Allocate colors
$pink = imagecolorallocate($canvas, 255, 105, 180);
$white = imagecolorallocate($canvas, 255, 255, 255);
$green = imagecolorallocate($canvas, 132, 135, 28);

// Draw three rectangles each with its own color
imagerectangle($canvas, 50, 50, 150, 150, $pink);
imagerectangle($canvas, 45, 60, 120, 100, $white);
imagerectangle($canvas, 100, 120, 75, 160, $green);

// Output and free from memory
header('Content-Type: image/jpeg');

imagejpeg($canvas);
imagedestroy($canvas);
?>