<?php

class Trimmer
{

  public function trim($image, $width, $height, $red = 255, $green = 255, $blue = 255)
  {
      $this->image = $image;
      $this->actualWidth = $width;
      $this->actualHeight = $height;

      $bgColor = imagecolorexact($image, $red, $green, $blue);

      $top = $this->getTopBound($bgColor);
      $bottom = $this->getBottomBound($bgColor);
      $left = $this->getLeftBound($bgColor);
      $right = $this->getRightBound($bgColor);

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

  private function getLeftBound($bgColor) {
        for($x = 0; $x < $this->actualWidth; ++$x) {
            for($y = 0; $y < $this->actualHeight; ++$y) {
                $color = imagecolorat($this->image, $x, $y);
                if ($color !== $bgColor) {
                    return $x;
                }
            }
        }
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
