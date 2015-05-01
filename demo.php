<?php

$rootPath = dirname(__FILE__);
require_once $rootPath . '/Trimmer.php';

$imageId = 'bike';

$imgFile = $rootPath . "/test/sample_images/{$imageId}.jpg";
$outFile = $rootPath . "/test/sample_images/{$imageId}-out.jpg";

$start = microtime(true);

$trimmer = new Trimmer();

$image = imagecreatefromjpeg($imgFile);
$width = imagesx($image);
$height = imagesy($image);

$trimmedImage = $trimmer->trimImageBorder($image, $width, $height);

imagejpeg($trimmedImage, $outFile);

echo 'Took ' . (microtime(true) - $start) . "\n";
