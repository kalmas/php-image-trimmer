<?php

class Trimmer
{

    public function getBounds($image, $width, $height, $red = 255, $green = 255, $blue = 255)
    {
        $colorToTrim = imagecolorexact($image, $red, $green, $blue);

        /**
         * Compare pixel at x, y match the color to trim.
         *
         * @param integer $x X coordinate.
         * @param integer $y Y coordinate.
         *
         * @return boolean True if matches.
         */
        $matchesColorToTrim = function ($x, $y) use ($image, $colorToTrim)
        {
            $color = imagecolorat($image, $x, $y);
            return $color == $colorToTrim;
        };

        // Fast path to avoid unnecessary work.
        // If top left pixel doesn't match color to trim, return full image bounds.
        if(! $matchesColorToTrim(0, 0)) {
            return [
                'left' => 0,
                'right' => $width,
                'top' => 0,
                'bottom' => $height
            ];
        }

        /**
         * Compare pixels along vertical line to the color to trim.
         *
         * @param integer $x        X coordinate.
         * @param integer $interval Number of pixels to skip between checks.
         *
         * @return boolean True if all match.
         */
        $matchAtX = function ($x, $interval) use ($matchesColorToTrim, $height)
        {
            for ($y = $interval; $y < $height; $y = $y + $interval) {
              if (! $matchesColorToTrim($x, $y)) {
                return false;
              }
            }

            return true;
        };

        /**
         * Compare pixels along horizontal line to the color to trim.
         *
         * @param integer $y        Y coordinate.
         * @param integer $interval Number of pixels to skip between checks.
         *
         * @return boolean True if all match.
         */
        $matchAtY = function ($y, $interval) use ($matchesColorToTrim, $width)
        {
            for ($x = $interval; $x < $width; $x = $x + $interval) {
              if (! $matchesColorToTrim($x, $y)) {
                return false;
              }
            }

            return true;
        };

        $bounds = [];
        $bounds['left'] = $this->findLowBound($width, $height, $matchAtX);
        $bounds['right'] = $this->findHighBound($width, $height, $matchAtX);
        $bounds['top'] = $this->findLowBound($height, $height, $matchAtY);
        $bounds['bottom'] = $this->findHighBound($height, $height, $matchAtY);

        return $bounds;
    }

    /**
     * Test if line intersecting given point matches color to trim.
     *
     * The logic here is the product of trial-and-error, it is ripe for further
     * optimization if you're feeling clever.
     *
     * @param integer  $location        Location on axis.
     * @param integer  $range           Range of intersecting line.
     * @param callable $matchAtLocation Function to use to match on intersecting line.
     *
     * @return boolean True if matches.
     */
    private function sampleIntersectingLine($location, $range, $matchAtLocation)
    {
        // Sample at fifths first,
        // this provides a chance to skip more intensive scan.
        if (! $matchAtLocation($location, ceil($range / 5))) {
            return false;
        }

        // Sample at three-hundredths, this number was picked as a pretty-good compromise between
        // speed and accuracy.
        if (! $matchAtLocation($location, ceil($range / 300))) {
            return false;
        }

        return true;
    }

    /**
     * Look for boundary border color working outward from center of image along one
     * axis toward the lower edge.
     *
     * Start by scanning at half the distance between center and edge,
     * continue to scan outward by halves until the edge of the image
     * is passed, then step back one pixel at a time until the exact boundary
     * is found.
     *
     * @param integer  $range             Range of axis being checked (i.e. height or width).
     * @param integer  $intersectingRange Range of intersecting axis.
     * @param callable $matchAtLocation   Function to use to match at each location along axis.
     *
     * @return number Lower bound.
     */
    private function findLowBound($range, $intersectingRange, $matchAtLocation)
    {
        // Scan downward by halves.
        $distance = $range / 2;
        $location = $distance;
        while (! $this->sampleIntersectingLine($location, $intersectingRange, $matchAtLocation)) {
            if ($location < 1) {
                // Reached the edge, abort.
                return 0;
            }

            $distance = $distance / 2;
            $location = $distance;
        }

        // Scan back upward in one pixel steps.
        $location = $location + 1;
        while ($this->sampleIntersectingLine($location, $intersectingRange, $matchAtLocation)) {
            $location = $location + 1;
        }

        return (int) floor($location);
    }

    /**
     * Look for boundary border color working outward from center of image along one
     * axis toward the upper edge.
     *
     * Start by scanning at half the distance between center and edge,
     * continue to scan outward by halves until the edge of the image
     * is passed, then step back one pixel at a time until the exact boundary
     * is found.
     *
     * @param integer  $range             Range of axis being checked (i.e. height or width).
     * @param integer  $intersectingRange Range of intersecting axis.
     * @param callable $matchAtLocation   Function to use to match at each location along axis.
     *
     * @return number Upper bound.
     */
    private function findHighBound($range, $intersectingRange, $matchAtLocation)
    {
        // Scan upward by halves.
        $distance = $range / 2;
        $location = $range - $distance;
        while (! $this->sampleIntersectingLine($location, $intersectingRange, $matchAtLocation)) {
            if ($location > ($range - 1)) {
                // Reached the edge, abort.
                return $range;
            }

            $distance = $distance / 2;
            $location = $range - $distance;
        }

        // Scan back downward in one pixel steps.
        $location = $location - 1;
        while ($this->sampleIntersectingLine($location, $intersectingRange, $matchAtLocation)) {
            $location = $location - 1;
        }

        return (int) floor($location);
    }
}
