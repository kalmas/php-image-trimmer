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
            for ($y = $interval; $y < $height; $y = $y + $interval) {
              if (! $test($x, $y)) {
                return false;
              }
            }

            return true;
        };

        $testAtY = function ($y, $interval) use ($test, $width)
        {
            for ($x = $interval; $x < $width; $x = $x + $interval) {
              if (! $test($x, $y)) {
                return false;
              }
            }

            return true;
        };

        $matchFunc = function ($location, $rangeMax, $testAtLocationFunc)
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

        $bounds = [];
        $bounds['left'] = $this->getLow($width, $height, $matchFunc, $testAtX);
        $bounds['right'] = $this->getHigh($width, $height, $matchFunc, $testAtX);
        $bounds['top'] = $this->getLow($height, $height, $matchFunc, $testAtY);
        $bounds['bottom'] = $this->getHigh($height, $height, $matchFunc, $testAtY);

        return $bounds;
    }


    private function getLow($max, $otherMax, $matchFunc, $testAtLocationFunc)
    {
        $distance = $max / 2;
        $loc = $distance;
        while (($loc > 1) && ! $matchFunc($loc, $otherMax, $testAtLocationFunc)) {
            $distance = $distance / 2;
            $loc = $distance;
        }

        $loc = $loc + 1;
        while ($matchFunc($loc, $otherMax, $testAtLocationFunc)) {
            $loc = $loc + 1;
        }

        return $loc - 1;
    }

    private function getHigh($max, $otherMax, $matchFunc, $testAtLocationFunc)
    {
        $distance = $max / 2;
        $loc = $max - $distance;
        while (($loc < ($max - 1)) && ! $matchFunc($loc, $otherMax, $testAtLocationFunc)) {
            $distance = $distance / 2;
            $loc = $max - $distance;
        }

        $loc = $loc - 1;
        while ($matchFunc($loc, $otherMax, $testAtLocationFunc)) {
            $loc = $loc - 1;
        }

        return $loc + 1;
    }
}
