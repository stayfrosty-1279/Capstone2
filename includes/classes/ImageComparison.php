<?php
namespace Admin\Includes\Classes;

class ImageComparison {
    private $hashSize = 16;
    private $threshold = 90;
    
    public function generateImageHash($imagePath) {
        try {
            $image = imagecreatefromstring(file_get_contents($imagePath));
            if (!$image) {
                throw new Exception("Unable to load image: $imagePath");
            }

            $resized = imagecreatetruecolor($this->hashSize, $this->hashSize);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, 
                             $this->hashSize, $this->hashSize,
                             imagesx($image), imagesy($image));

            $averageValue = 0;
            $grayMatrix = array();
            
            for ($y = 0; $y < $this->hashSize; $y++) {
                for ($x = 0; $x < $this->hashSize; $x++) {
                    $rgb = imagecolorat($resized, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    
                    $grayValue = floor(($r + $g + $b) / 3);
                    $grayMatrix[$y][$x] = $grayValue;
                    $averageValue += $grayValue;
                }
            }
            
            $averageValue /= ($this->hashSize * $this->hashSize);

            $hash = '';
            for ($y = 0; $y < $this->hashSize; $y++) {
                for ($x = 0; $x < $this->hashSize; $x++) {
                    $hash .= ($grayMatrix[$y][$x] > $averageValue) ? '1' : '0';
                }
            }

            imagedestroy($resized);
            imagedestroy($image);

            return $hash;
        } catch (Exception $e) {
            error_log("Error generating image hash: " . $e->getMessage());
            return false;
        }
    }

    public function compareImages($image1Path, $image2Path) {
        try {
            $hash1 = $this->generateImageHash($image1Path);
            $hash2 = $this->generateImageHash($image2Path);

            if (!$hash1 || !$hash2) {
                throw new Exception("Failed to generate hash for one or both images");
            }

            $hammingDistance = 0;
            $hashLength = strlen($hash1);
            
            for ($i = 0; $i < $hashLength; $i++) {
                if ($hash1[$i] !== $hash2[$i]) {
                    $hammingDistance++;
                }
            }

            $similarity = (1 - ($hammingDistance / $hashLength)) * 100;

            return [
                'similarity' => round($similarity, 2),
                'is_match' => $similarity >= $this->threshold,
                'hash1' => $hash1,
                'hash2' => $hash2,
                'hamming_distance' => $hammingDistance
            ];
        } catch (Exception $e) {
            error_log("Error comparing images: " . $e->getMessage());
            return [
                'error' => $e->getMessage(),
                'similarity' => 0,
                'is_match' => false
            ];
        }
    }

    public function setThreshold($threshold) {
        $this->threshold = max(0, min(100, $threshold));
    }
}
?>