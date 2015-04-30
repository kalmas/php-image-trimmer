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

$image = $trimmer->trim($image, $width, $height);

imagejpeg($image, $outFile);

echo 'Took ' . (microtime(true) - $start) . "\n";
