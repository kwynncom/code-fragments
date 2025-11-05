<?php
require '/opt/composer/vendor/autoload.php';
use thiagoalessio\TesseractOCR\TesseractOCR;

final class WordleOCR
{
    public const TEMP_GRID   = '/tmp/wordle_grid.png';
    public const TEMP_KBD    = '/tmp/wordle_keyboard.png';

    public static function read(string $inputFile): array
    {
        if (!is_file($inputFile)) {
            return ['error' => "File not found: $inputFile"];
        }

        $orig = @imagecreatefrompng($inputFile);
        if (!$orig) {
            return ['error' => "Cannot load PNG: $inputFile"];
        }

        $w = imagesx($orig);
        $h = imagesy($orig);

        /* --------------------------------------------------------------
           1. Grayscale + Otsu → B&W (same for both regions)
           -------------------------------------------------------------- */
        $gray = imagecreatetruecolor($w, $h);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($orig, $x, $y);
                $r   = ($rgb >> 16) & 0xFF;
                $g   = ($rgb >> 8)  & 0xFF;
                $b   = $rgb & 0xFF;
                $val = (int)(0.299 * $r + 0.587 * $g + 0.114 * $b);
                $col = imagecolorallocate($gray, $val, $val, $val);
                imagesetpixel($gray, $x, $y, $col);
            }
        }

        // Otsu
        $hist = array_fill(0, 256, 0);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) $hist[imagecolorat($gray, $x, $y) & 0xFF]++;
        }
        $total = $w * $h;
        $sum   = array_sum(array_map(fn($i,$c)=>$i*$c, array_keys($hist), $hist));
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

        $black = imagecolorallocate($orig, 0,0,0);
        $white = imagecolorallocate($orig, 255,255,255);

        // --------------------------------------------------------------
        // 2. Create two working canvases
        // --------------------------------------------------------------
        $gridImg = imagecreatetruecolor($w, $h);
        $kbdImg  = imagecreatetruecolor($w, $h);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $val = imagecolorat($gray, $x, $y) & 0xFF;
                $c   = $val > $threshold ? $white : $black;
                imagesetpixel($gridImg, $x, $y, $c);
                imagesetpixel($kbdImg , $x, $y, $c);
            }
        }

        // --------------------------------------------------------------
        // 3. Helper: flood-fill from a point
        // --------------------------------------------------------------
        $flood = function (&$img, $sx, $sy) use ($w,$h,$white,$black) {
            $q = [[$sx,$sy]];
            $v = [];
            while ($q) {
                [$cx,$cy] = array_pop($q);
                $k = "$cx,$cy";
                if (isset($v[$k])) continue;
                $v[$k] = true;
                if ($cx<0||$cx>=$w||$cy<0||$cy>=$h) continue;
                if (imagecolorat($img,$cx,$cy) !== $white) continue;
                imagesetpixel($img,$cx,$cy,$black);
                $q[] = [$cx+1,$cy]; $q[] = [$cx-1,$cy];
                $q[] = [$cx,$cy+1]; $q[] = [$cx,$cy-1];
            }
        };

        // --------------------------------------------------------------
        // 4. Clean the **guess grid** – remove borders
        // --------------------------------------------------------------
        for ($x = 0; $x < $w; $x++) {
            if (imagecolorat($gridImg,$x,0) === $white) $flood($gridImg,$x,0);
            if (imagecolorat($gridImg,$x,$h-1) === $white) $flood($gridImg,$x,$h-1);
        }
        for ($y = 0; $y < $h; $y++) {
            if (imagecolorat($gridImg,0,$y) === $white) $flood($gridImg,0,$y);
            if (imagecolorat($gridImg,$w-1,$y) === $white) $flood($gridImg,$w-1,$y);
        }

        // --------------------------------------------------------------
        // 5. Clean the **keyboard** – fill background
        // --------------------------------------------------------------
        // Keyboard is at the bottom of the screenshot
        $kbdTop = (int)($h * 0.70);
        for ($x = 0; $x < $w; $x++) {
            if (imagecolorat($kbdImg,$x,$kbdTop) === $white) $flood($kbdImg,$x,$kbdTop);
        }

        // --------------------------------------------------------------
        // 6. Invert keyboard letters that are **surrounded**
        // --------------------------------------------------------------
        for ($x = 1; $x < $w-1; $x++) {
            for ($y = $kbdTop+1; $y < $h-1; $y++) {
                if (imagecolorat($kbdImg,$x,$y) !== $black) continue;
                // 8-neighbour check
                $surrounded = true;
                for ($dx = -1; $dx <= 1; $dx++) {
                    for ($dy = -1; $dy <= 1; $dy++) {
                        if ($dx===0 && $dy===0) continue;
                        if (imagecolorat($kbdImg,$x+$dx,$y+$dy) === $white) {
                            $surrounded = false;
                            break 2;
                        }
                    }
                }
                if ($surrounded) imagesetpixel($kbdImg,$x,$y,$white);
            }
        }

        // --------------------------------------------------------------
        // 7. Save temporary images
        // --------------------------------------------------------------
        imagepng($gridImg, self::TEMP_GRID);
        imagepng($kbdImg , self::TEMP_KBD);

        // --------------------------------------------------------------
        // 8. OCR both regions
        // --------------------------------------------------------------
        $gridOCR = (new TesseractOCR(self::TEMP_GRID))
            ->lang('eng')
            ->psm(6)
            ->allowlist(range('A','Z'))
            ->run();

        $kbdOCR  = (new TesseractOCR(self::TEMP_KBD))
            ->lang('eng')
            ->psm(6)
            ->allowlist(range('A','Z'))
            ->run();

        // --------------------------------------------------------------
        // 9. Cleanup
        // --------------------------------------------------------------
        imagedestroy($orig);
        imagedestroy($gray);
        imagedestroy($gridImg);
        imagedestroy($kbdImg);

        return [
            'grid_text'   => trim($gridOCR),
            'keyboard_text'=> trim($kbdOCR),
            'grid_image'  => self::TEMP_GRID,
            'kbd_image'   => self::TEMP_KBD,
        ];
    }
}

/* -----------------------------------------------------------------
   USAGE
   ----------------------------------------------------------------- */
$f = '/home/' . get_current_user() . '/Screenshots/Screenshot from 2025-11-01 01-51-31.png';

echo "Processing: $f\n";
$result = WordleOCR::read($f);

echo "Grid OCR:\n{$result['grid_text']}\n\n";
echo "Keyboard OCR:\n{$result['keyboard_text']}\n\n";
echo "Saved grid image: {$result['grid_image']}\n";
echo "Saved keyboard image: {$result['kbd_image']}\n";