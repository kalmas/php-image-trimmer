<?php

$rootPath = dirname(dirname(__FILE__));
require_once $rootPath . '/Trimmer.php';

class TrimmerTest extends PHPUnit_Framework_TestCase
{
    private $imgPath;

    /**
     * Trimmer under test.
     *
     * @var Trimmer
     */
    private $trimmer;

    public function setUp()
    {
        $this->imgPath = dirname(__FILE__) . '/sample_images';
        $this->trimmer = new Trimmer();
    }

    public function testOnBikeImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/bike.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getBounds($image, $width, $height);

        // $duration = microtime(true) - $start;
        // echo $duration;

        // $this->assertLessThan(0, $duration);

        $expected = [
            'left' => 176,
            'right' => 5015,
            'top' => 680,
            'bottom' => 3615
        ];
        $this->assertEquals($expected, $bounds);
    }

    public function testOnFpImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/fp.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getBounds($image, $width, $height);

        // $duration = microtime(true) - $start;
        // echo $duration;

        // $this->assertLessThan(0, $duration);

        $expected = [
            'left' => 656,
            'right' => 1351,
            'top' => 0,
            'bottom' => 431
        ];
        $this->assertEquals($expected, $bounds);
    }

}