<?php

class Trimmer
{

  public function trim($image, $width, $height, $red = 255, $green = 255, $blue = 255)
  {
      $bgColor = imagecolorexact($image, $red, $green, $blue);

      $this->image = $image;
      $this->actualWidth = $width;
      $this->actualHeight = $height;
      $this->bgColor = $bgColor;

      $test = function ($x, $y) use ($image, $bgColor)
      {
        $color = imagecolorat($image, $x, $y);
        return $color == $bgColor;
      };

      $testAtX = function ($x, $interval) use ($test, $height)
      {
        echo $x . " ";
        for ($y = 0; $y < $height; $y = $y + $interval) {
          if (! $test($x, $y)) {
            return false;
          }
        }

        return true;
      };

      $testAtY = function ($y, $interval) use ($test, $width)
      {
        echo $y . " ";
        for ($x = 0; $x < $width; $x = $x + $interval) {
          if (! $test($x, $y)) {
            return false;
          }
        }

        return true;
      };

      $left = $this->getLow($width, $testAtX);
      echo "Left {$left} \n";

      $right = $this->getHigh($width, $testAtX);
      echo "Right {$right} \n";

      $top = $this->getLow($height, $testAtY);
      echo "Top {$top} \n";

      $bottom = $this->getHigh($height, $testAtY);
      echo "Bottom {$bottom} \n";

      $newWidth = 1 + $right - $left;
      $newHeight = 1 + $bottom - $top;

      $trimmedImage = imagecreatetruecolor($newWidth, $newHeight);
      $bgColor = imagecolorallocate(
          $trimmedImage,
          $red,
          $green,
          $blue
      );
      imagefill($trimmedImage, 0, 0, $bgColor);
      imagecopy($trimmedImage, $image, 0, 0, $left, $top, $newWidth, $newHeight);

      return $trimmedImage;
  }


  private function getLow($max, $test)
  {
    $interval = $max / 50;
    // While not a clear meridian, bisect.
    $distance = $max / 2;
    $v = $distance;
    while (! $test($v, $interval)) {
      $distance = $distance / 2;
      $v = $distance;
    }

    // Step back up until we find blocked meridian.
    $v = $v + 1;
    while ($test($v, $interval)) {
      $v = $v + 1;
    }

    return $v - 1;
  }

  private function getHigh($max, $test)
  {
    $interval = $max / 50;

    // While not a clear meridian, bisect.
    $distance = $max / 2;
    $v = $max - $distance;
    while (! $test($v, $interval)) {
      $distance = $distance / 2;
      $v = $max - $distance;
    }

    // Step back down until we find blocked meridian.
    $v = $v - 1;
    while ($test($v, $interval)) {
      $v = $v - 1;
    }

    return $v + 1;
  }

}
