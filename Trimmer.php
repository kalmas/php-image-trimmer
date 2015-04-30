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
        // echo 'x' . $x . " ";

        for ($y = $interval; $y < $height; $y = $y + $interval) {
          if (! $test($x, $y)) {
            return false;
          }
        }

        return true;
      };

      $testAtY = function ($y, $interval) use ($test, $width)
      {
        // echo 'y' . $y . " ";

        for ($x = $interval; $x < $width; $x = $x + $interval) {
          if (! $test($x, $y)) {
            return false;
          }
        }

        return true;
      };

      $matchFunc = function ($location, $rangeMax, $testAtLocationFunc) use ($test)
      {
        // Sample fifths.
        if (! $testAtLocationFunc($location, ceil($rangeMax / 5))) {
          return false;
        }

        // Sample at hundredths.
        if (! $testAtLocationFunc($location, ceil($rangeMax / 100))) {
            return false;
        }

        return true;
      };

      $left = $this->getLow($width, $height, $matchFunc, $testAtX);
      echo "Left {$left} \n";

      $right = $this->getHigh($width, $height, $matchFunc, $testAtX);
      echo "Right {$right} \n";

      $top = $this->getLow($height, $height, $matchFunc, $testAtY);
      echo "Top {$top} \n";

      $bottom = $this->getHigh($height, $height, $matchFunc, $testAtY);
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


  private function getLow($max, $otherMax, $matchFunc, $testAtLocationFunc)
  {
    // While not a matching intersection, bisect.
    $distance = $max / 2;
    $loc = $distance;
    while (($loc > 1) && ! $matchFunc($loc, $otherMax, $testAtLocationFunc)) {
      $distance = $distance / 2;
      $loc = $distance;
    }

    // Step back up until we find blocked meridian.
    $loc = $loc + 1;
    while ($matchFunc($loc, $otherMax, $testAtLocationFunc)) {
      $loc = $loc + 1;
    }

    return $loc - 1;
  }

  private function getHigh($max, $otherMax, $matchFunc, $testAtLocationFunc)
  {
    // While not a clear meridian, bisect.
    $distance = $max / 2;
    $v = $max - $distance;
    while (($v < ($max - 1)) && ! $matchFunc($v, $otherMax, $testAtLocationFunc)) {
      $distance = $distance / 2;
      $v = $max - $distance;
    }

    // Step back down until we find blocked meridian.
    $v = $v - 1;
    while ($matchFunc($v, $otherMax, $testAtLocationFunc)) {
      $v = $v - 1;
    }

    return $v + 1;
  }

}
