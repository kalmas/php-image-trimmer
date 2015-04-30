<?php

$rootPath = dirname(__FILE__);
require_once $rootPath . '/Trimmer.php';

$imgFile = $rootPath . '/fp.jpg';
$outFile = $rootPath . '/out.jpg';

$start = microtime(true);

$trimmer = new Trimmer();

$image = imagecreatefromjpeg($imgFile);
$width = imagesx($image);
$height = imagesy($image);

$bounds = $trimmer->trim($image, $width, $height);

$newWidth = 1 + $bounds['right'] - $bounds['left'];
$newHeight = 1 + $bounds['bottom'] - $bounds['top'];

$trimmedImage = imagecreatetruecolor($newWidth, $newHeight);
$bgColor = imagecolorallocate(
  $trimmedImage,
  255,
  255,
  255
);
imagefill($trimmedImage, 0, 0, $bgColor);
imagecopy($trimmedImage, $image, 0, 0, $bounds['left'], $bounds['top'], $newWidth, $newHeight);

imagejpeg($trimmedImage, $outFile);


echo 'Took ' . (microtime(true) - $start) . "\n";
