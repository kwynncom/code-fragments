<?php
require '/opt/composer/vendor/autoload.php';

use thiagoalessio\TesseractOCR\TesseractOCR;

final class WordleOCR
{
    public const TEMP_FILE = '/tmp/wordle_clean.png';

    public static function read(string $inputFile): string
    {
        if (!is_file($inputFile)) {
            return "ERROR: File not found: $inputFile\n";
        }

        $img = @imagecreatefrompng($inputFile);
        if (!$img) {
            return "ERROR: Cannot load PNG: $inputFile\n";
        }

        $w = imagesx($img);
        $h = imagesy($img);

        // 1. Grayscale
        $gray = imagecreatetruecolor($w, $h);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8)  & 0xFF;
                $b = $rgb & 0xFF;
                $val = (int)(0.299 * $r + 0.587 * $g + 0.114 * $b);
                $color = imagecolorallocate($gray, $val, $val, $val);
                imagesetpixel($gray, $x, $y, $color);
            }
        }

        // 2. Otsu Threshold
        $hist = array_fill(0, 256, 0);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $val = imagecolorat($gray, $x, $y) & 0xFF;
                $hist[$val]++;
            }
        }
        $total = $w * $h;
        $sum = array_sum(array_map(fn($i, $c) => $i * $c, array_keys($hist), $hist));
        $sumB = $wB = $wF = $max = $threshold = 0;
        for ($i = 0; $i < 256; $i++) {
            $wB += $hist[$i];
            if ($wB == 0) continue;
            $wF = $total - $wB;
            if ($wF == 0) break;
            $sumB += $i * $hist[$i];
            $mB = $sumB / $wB;
            $mF = ($sum - $sumB) / $wF;
            $between = $wB * $wF * ($mB - $mF) ** 2;
            if ($between > $max) { $max = $between; $threshold = $i; }
        }

        // 3. B&W
        $bw = imagecreatetruecolor($w, $h);
        $black = imagecolorallocate($bw, 0, 0, 0);
        $white = imagecolorallocate($bw, 255, 255, 255);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $val = imagecolorat($gray, $x, $y) & 0xFF;
                imagesetpixel($bw, $x, $y, $val > $threshold ? $white : $black);
            }
        }

        // 4. Edge white → black
        for ($x = 0; $x < $w; $x++) {
            if (imagecolorat($bw, $x, 0) === $white) imagesetpixel($bw, $x, 0, $black);
            if (imagecolorat($bw, $x, $h-1) === $white) imagesetpixel($bw, $x, $h-1, $black);
        }
        for ($y = 0; $y < $h; $y++) {
            if (imagecolorat($bw, 0, $y) === $white) imagesetpixel($bw, 0, $y, $black);
            if (imagecolorat($bw, $w-1, $y) === $white) imagesetpixel($bw, $w-1, $y, $black);
        }

        // 5. All non-white → black
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                if (imagecolorat($bw, $x, $y) !== $white) {
                    imagesetpixel($bw, $x, $y, $black);
                }
            }
        }

        // 6. Save
        imagepng($bw, self::TEMP_FILE);

        // 7. OCR
        $text = (new TesseractOCR(self::TEMP_FILE))
            ->lang('eng')
            ->psm(6)
            ->allowlist(range('A', 'Z'))
            ->run();

        // 8. Cleanup
        imagedestroy($img);
        imagedestroy($gray);
        imagedestroy($bw);

        return $text;
    }
}

// ——— YOUR FILE ———
$f = '/home/' . get_current_user() . '/Screenshots/Screenshot from 2025-11-01 01-51-31.png';

echo "Processing: $f\n";
echo "Saved to: " . WordleOCR::TEMP_FILE . "\n\n";
echo WordleOCR::read($f);