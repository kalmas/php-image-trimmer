<?php

$rootPath = dirname(__FILE__);
require_once $rootPath . '/Trimmer.php';

$imgFile = $rootPath . '/bike.jpg';
$outFile = $rootPath . '/out2.jpg';

$start = microtime(true);

$im = new Imagick($imgFile);
$im->trimImage(0);

$im->writeImage($outFile);

echo 'Took ' . (microtime(true) - $start) . "\n";
