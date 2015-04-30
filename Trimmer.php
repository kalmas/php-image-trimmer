<?php

class Trimmer
{

  public function trim($image, $width, $height, $red = 255, $green = 255, $blue = 255)
  {
      $bgColor = imagecolorexact($image, $red, $green, $blue);

      $this->image = $image;
      $this->actualWidth = $width;
      $this->actualHeight = $height;

      $this->meridian = $width / 2;
      $this->equator = $height / 2;

      $this->bgColor = $bgColor;

      $top = $this->getTopBound($bgColor);
      $bottom = $this->getBottomBound($bgColor);
      $left = $this->getLeftBound($bgColor);
      $right = $this->getRightBound($bgColor);

      $newWidth = 1 + $right - $left;
      $newHeight = 1 + $bottom - $top;
      // $trimmedImage = imagecreatetruecolor($newWidth, $newHeight);
      // $bgColor = imagecolorallocate(
      //     $trimmedImage,
      //     $red,
      //     $green,
      //     $blue
      // );
      // imagefill($trimmedImage, 0, 0, $bgColor);
      // imagecopy($trimmedImage, $image, 0, 0, $left, $top, $newWidth, $newHeight);

      return $trimmedImage;
  }


  private function test($x, $y)
  {
    $color = imagecolorat($this->image, $x, $y);
    return $color == $this->bgColor;
  }

  private function testAtX($x)
  {
    for($y = 0; $y < $this->actualHeight; $y++) {
      if (! $this->test($x, $y)) {
        return false;
      }
    }

    return true;
  }

  private function getLeftBound($bgColor)
  {
    $x = $this->actualWidth / 2;

    // While not a clear meridian, bisect.
    while(! $this->testAtX($x))
    {
      $x = $x / 2;
    }

    // Step back up until we find blocked meridian.
    $x = $x + 1;
    while($this->testAtX($x))
    {
      $x = $x + 1;
    }

    return $x - 1;
  }

  private function getRightBound($bgColor) {
      for($x = $this->actualWidth - 1; $x >= 0; --$x) {
          for($y = 0; $y < $this->actualHeight; ++$y) {
              $color = imagecolorat($this->image, $x, $y);
              if ($color !== $bgColor) {
                  return $x;
              }
          }
      }
  }

  private function getTopBound($bgColor) {
      for($y = 0; $y < $this->actualHeight; ++$y) {
          for($x = 0; $x < $this->actualWidth; ++$x) {
              $color = imagecolorat($this->image, $x, $y);
              if ($color !== $bgColor) {
                  return $y;
              }
          }
      }
  }

  private function getBottomBound($bgColor) {
      for($y = $this->actualHeight - 1; $y >= 0; --$y) {
          for($x = 0; $x < $this->actualWidth; ++$x) {
              $color = imagecolorat($this->image, $x, $y);
              if ($color !== $bgColor) {
                  return $y;
              }
          }
      }
  }

}
