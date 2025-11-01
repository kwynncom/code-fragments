<?php

// Grok's attempt.  Gets close, but it can't tell white borders / dividing lines from white text.

require '/opt/composer/vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

final class WordleOCR
{
    public static function read(string $inputFile): string
    {
        if (!is_file($inputFile)) {
            return "ERROR: File not found: $inputFile\n";
        }

        // 1. Load image
        $img = @imagecreatefrompng($inputFile);
        if (!$img) {
            return "ERROR: Cannot load PNG: $inputFile\n";
        }

        $w = imagesx($img);
        $h = imagesy($img);

        // 2. Convert to grayscale
        $gray = imagecreatetruecolor($w, $h);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8)  & 0xFF;
                $b = $rgb & 0xFF;
                $grayVal = (int)(0.299 * $r + 0.587 * $g + 0.114 * $b);
                $color = imagecolorallocate($gray, $grayVal, $grayVal, $grayVal);
                imagesetpixel($gray, $x, $y, $color);
            }
        }

        // 3. Otsu Thresholding
        $histogram = array_fill(0, 256, 0);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $val = imagecolorat($gray, $x, $y) & 0xFF;
                $histogram[$val]++;
            }
        }

        $total = $w * $h;
        $sum = 0;
        for ($i = 0; $i < 256; $i++) $sum += $i * $histogram[$i];

        $sumB = 0;
        $wB = 0;
        $wF = 0;
        $max = 0;
        $threshold = 0;

        for ($i = 0; $i < 256; $i++) {
            $wB += $histogram[$i];
            if ($wB == 0) continue;
            $wF = $total - $wB;
            if ($wF == 0) break;

            $sumB += $i * $histogram[$i];
            $mB = $sumB / $wB;
            $mF = ($sum - $sumB) / $wF;
            $between = $wB * $wF * ($mB - $mF) * ($mB - $mF);
            if ($between > $max) {
                $threshold = $i;
                $max = $between;
            }
        }

        // 4. Apply threshold → B&W
        $bw = imagecreatetruecolor($w, $h);
        $black = imagecolorallocate($bw, 0, 0, 0);
        $white = imagecolorallocate($bw, 255, 255, 255);

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $val = imagecolorat($gray, $x, $y) & 0xFF;
                imagesetpixel($bw, $x, $y, $val > $threshold ? $white : $black);
            }
        }

        // 5. Save to /tmp
        $temp = '/tmp/wordle_bw.png';
        imagepng($bw, $temp);

        // 6. OCR
        $text = (new TesseractOCR($temp))
            ->lang('eng')
            ->psm(6)
            ->allowlist(range('A', 'Z'))
            ->run();

        // 7. Cleanup
        imagedestroy($img);
        imagedestroy($gray);
        imagedestroy($bw);
        // @unlink($temp);

        return $text;
    }
}

// ——— YOUR FILE ———
$f = '/home/' . get_current_user() . '/Screenshots/Screenshot from 2025-11-01 01-51-31.png';

echo "Processing: $f\n\n";
echo WordleOCR::read($f);