<?php

$rootPath = dirname(dirname(__FILE__));
require_once $rootPath . '/Trimmer.php';

/**
 * Tests of Trimmer.
 */
class TrimmerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Path to sample images.
     *
     * @var string
     */
    private $imgPath;

    /**
     * Trimmer under test.
     *
     * @var Trimmer
     */
    private $trimmer;

    /**
     * Called before each test.
     *
     * @return void
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->imgPath = dirname(__FILE__) . '/sample_images';
        $this->trimmer = new Trimmer();
    }

    /**
     * Trim large image with lots of white space.
     *
     * @return void
     */
    public function testTrimBikeImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/bike.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getImageBorderBounds($image, $width, $height);

        $duration = microtime(true) - $start;

        $this->assertLessThan(2, $duration, 'Trimming took too long!');

        $expected = [
            'left' => 176,
            'right' => 5015,
            'top' => 680,
            'bottom' => 3615,
            'trimmed' => true
        ];
        $this->assertEquals($expected, $bounds);
    }

    /**
     * Trim oblong image.
     *
     * @return void
     */
    public function testTrimFpImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/fp.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getImageBorderBounds($image, $width, $height);

        $duration = microtime(true) - $start;

        $this->assertLessThan(2, $duration, 'Trimming took too long!');

        $expected = [
            'left' => 656,
            'right' => 1351,
            'top' => 0,
            'bottom' => 431,
            'trimmed' => true
        ];
        $this->assertEquals($expected, $bounds);
    }

    /**
     * Trim around a square.
     *
     * @return void
     */
    public function testTrimBoxImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/box.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getImageBorderBounds($image, $width, $height);

        $duration = microtime(true) - $start;

        $this->assertLessThan(2, $duration, 'Trimming took too long!');

        $expected = [
            'left' => 1381,
            'right' => 2906,
            'top' => 1245,
            'bottom' => 2594,
            'trimmed' => true
        ];
        $this->assertEquals($expected, $bounds);
    }

    /**
     * Trim a small image.
     *
     * @return void
     */
    public function testTrimSmileImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/smile.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getImageBorderBounds($image, $width, $height);

        $duration = microtime(true) - $start;

        $this->assertLessThan(2, $duration, 'Trimming took too long!');

        $expected = [
            'left' => 42,
            'right' => 175,
            'top' => 16,
            'bottom' => 183,
            'trimmed' => true
        ];
        $this->assertEquals($expected, $bounds);
    }

    /**
     * Trim image that is one solid color.
     *
     * @return void
     */
    public function testTrimSolidImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/solid.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getImageBorderBounds($image, $width, $height);

        $duration = microtime(true) - $start;

        $this->assertLessThan(2, $duration, 'Trimming took too long!');

        $expected = [
            'left' => 0,
            'right' => 10,
            'top' => 0,
            'bottom' => 10,
            'trimmed' => false
        ];
        $this->assertEquals($expected, $bounds);
    }

    /**
     * Trim image with top left pixel set to non-matching color.
     *
     * @return void
     */
    public function testOnHelloImage()
    {
        $image = imagecreatefromjpeg($this->imgPath . '/hello.jpg');
        $width = imagesx($image);
        $height = imagesy($image);

        $start = microtime(true);

        $bounds = $this->trimmer->getImageBorderBounds($image, $width, $height);

        $duration = microtime(true) - $start;

        $this->assertLessThan(2, $duration, 'Trimming took too long!');

        $expected = [
            'left' => 0,
            'right' => 500,
            'top' => 0,
            'bottom' => 500,
            'trimmed' => false
        ];
        $this->assertEquals($expected, $bounds);
    }
}
