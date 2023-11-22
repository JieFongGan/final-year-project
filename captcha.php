<?php
session_start();

// Generate a random string
$captcha_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

// Store the captcha value in session
$_SESSION["captcha_code"] = $captcha_code;

// Create a captcha image
$width = 100;
$height = 40;
$image = imagecreatetruecolor($width, $height);

// Set colors
$background_color = imagecolorallocate($image, 255, 255, 255); // white
$text_color = imagecolorallocate($image, 0, 0, 0); // black

// Fill the background with the background color
imagefill($image, 0, 0, $background_color);

// Write the captcha string on the image
imagestring($image, 5, 5, 5, $captcha_code, $text_color);

// Output the image
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>