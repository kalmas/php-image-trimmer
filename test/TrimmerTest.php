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

        $duration = microtime(true) - $start;
        echo $duration . "\n";

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

        $duration = microtime(true) - $start;
        echo $duration . "\n";

        // $this->assertLessThan(0, $duration);

        $expected = [
            'left' => 656,
            'right' => 1351,
            'top' => 0,
            'bottom' => 431
        ];
        $this->assertEquals($expected, $bounds);
    }

    public function testOnBoxImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/box.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getBounds($image, $width, $height);

        $duration = microtime(true) - $start;
        echo $duration . "\n";

        // $this->assertLessThan(0, $duration);

        $expected = [
            'left' => 1381,
            'right' => 2906,
            'top' => 1245,
            'bottom' => 2594
        ];
        $this->assertEquals($expected, $bounds);
    }

    public function testOnSmileImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/smile.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getBounds($image, $width, $height);

        $duration = microtime(true) - $start;
        echo $duration . "\n";

        // $this->assertLessThan(0, $duration);

        $expected = [
            'left' => 42,
            'right' => 175,
            'top' => 16,
            'bottom' => 183
        ];
        $this->assertEquals($expected, $bounds);
    }

    public function testOnSolidImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/solid.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getBounds($image, $width, $height);

        $duration = microtime(true) - $start;
        echo $duration . "\n";

        // $this->assertLessThan(0, $duration);

        $expected = [
            'left' => 0,
            'right' => 10,
            'top' => 0,
            'bottom' => 10
        ];
        $this->assertEquals($expected, $bounds);
    }

    public function testOnHelloImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/hello.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getBounds($image, $width, $height);

        $duration = microtime(true) - $start;
        echo $duration . "\n";

        // $this->assertLessThan(0, $duration);

        $expected = [
            'left' => 0,
            'right' => 500,
            'top' => 0,
            'bottom' => 500
        ];
        $this->assertEquals($expected, $bounds);
    }

}