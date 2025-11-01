<?php

/**
 * WordleColorPalette – FINAL, OTHER ≤ 1.6%
 * -----------------------------------------
 * - Source: /tmp/w/Wordle/*.png
 * - "Other" pixels ≤ 1.6%
 * - Integer-keyed
 * - Early exit: filesize → total_pixels → max_colors → other%
 */
final class WordleColorPalette
{
    const MARGIN_PERCENT     = 10;
    const MAX_COLORS         = 1100;
    const MAX_OTHER_PERCENT  = 1.6;  // ≤ 1.6%

    const WHITE     = 0xFFFFFF;
    const GREEN     = 0x6AAA64;
    const YELLOW    = 0xC9B458;
    const ALL_WRONG = 0x787C7E;
    const UNUSED    = 0xD3D6DA;
    const BLACK     = 0x000000;

    private const COLOR_LIMITS = [
        self::WHITE     => [219038, 355333],
        self::UNUSED    => [48489, 125903],
        self::ALL_WRONG => [43893, 178072],
        self::GREEN     => [49592, 113885],
        self::YELLOW    => [0,     33285],
        self::BLACK     => [1298,   5680],
    ];

    private const METRICS = [
        'total_pixels' => [520000, 650000],
        'filesize'     => [ 20000,  50000],
    ];

    private static function ranges(): array
    {
        static $cache = null;
        if ($cache !== null) return $cache;

        $m = self::MARGIN_PERCENT / 100;
        $cache = ['colors' => [], 'metrics' => []];

        foreach (self::COLOR_LIMITS as $rgb => [$min, $max]) {
            $cache['colors'][$rgb] = [(int)($min * (1 - $m)), (int)($max * (1 + $m))];
        }

        foreach (self::METRICS as $k => [$min, $max]) {
            $cache['metrics'][$k] = [(int)($min * (1 - $m)), (int)($max * (1 + $m))];
        }

        return $cache;
    }

    // ——— PUBLIC: Validate image ———
    public static function isWordleImage(string $file): bool
    {
        // ——— 1. FILESIZE ———
        if (!is_file($file)) return false;
        $filesize = filesize($file);
        [$minFs, $maxFs] = self::ranges()['metrics']['filesize'];
        if ($filesize < $minFs || $filesize > $maxFs) return false;

        // ——— 2. LOAD IMAGE ———
        if (!extension_loaded('gd')) return false;
        $img = @imagecreatefrompng($file);
        if (!$img) return false;

        $w = imagesx($img); $h = imagesy($img);
        $totalPixels = $w * $h;
        [$minPx, $maxPx] = self::ranges()['metrics']['total_pixels'];
        if ($totalPixels < $minPx || $totalPixels > $maxPx) {
            imagedestroy($img);
            return false;
        }

        // ——— 3. MAX_COLORS + OTHER% (early) ———
        $counts = [];
        $unique = 0;
        $namedSum = 0;
        $namedColors = [self::WHITE, self::UNUSED, self::ALL_WRONG, self::GREEN, self::YELLOW, self::BLACK];

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y) & 0xFFFFFF;
                if (!isset($counts[$rgb])) {
                    $unique++;
                    if ($unique > self::MAX_COLORS) {
                        imagedestroy($img);
                        return false;
                    }
                }
                $counts[$rgb] = ($counts[$rgb] ?? 0) + 1;
                if (in_array($rgb, $namedColors, true)) {
                    $namedSum++;
                }
            }
        }
        imagedestroy($img);

        $otherSum = $totalPixels - $namedSum;
        $otherPct = $totalPixels > 0 ? ($otherSum / $totalPixels) * 100 : 0;
        if ($otherPct > self::MAX_OTHER_PERCENT) {
            return false;
        }

        // ——— 4. WHITE = #1 ———
        arsort($counts);
        $top = array_keys($counts);
        if ($top[0] !== self::WHITE) return false;

        // ——— 5. GREEN, ALL_WRONG, UNUSED in 2–4 ———
        $pos2to4 = array_slice($top, 1, 3);
        $req = [self::GREEN, self::ALL_WRONG, self::UNUSED];
        foreach ($req as $c) {
            if (!in_array($c, $pos2to4, true)) return false;
        }

        // ——— 6. COLOR RANGES ———
        foreach (self::ranges()['colors'] as $rgb => [$min, $max]) {
            $cnt = $counts[$rgb] ?? 0;
            if ($cnt < $min || $cnt > $max) return false;
        }

        return true;
    }

    // ——— DEBUG WITH % BREAKDOWN ———
    public static function debug(string $file): void
    {
        if (!is_file($file)) { echo "Not file: $file\n"; return; }

        $filesize = filesize($file);
        $img = @imagecreatefrompng($file);
        if (!$img) { echo "Not PNG: $file\n"; return; }

        $w = imagesx($img); $h = imagesy($img);
        $total = $w * $h;
        $counts = [];
        $unique = 0;
        $namedSum = 0;
        $namedColors = [self::WHITE, self::UNUSED, self::ALL_WRONG, self::GREEN, self::YELLOW, self::BLACK];

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y) & 0xFFFFFF;
                if (!isset($counts[$rgb])) $unique++;
                $counts[$rgb] = ($counts[$rgb] ?? 0) + 1;
                if (in_array($rgb, $namedColors, true)) $namedSum++;
            }
        }
        imagedestroy($img);

        $otherSum = $total - $namedSum;
        $otherPct = $total > 0 ? ($otherSum / $total) * 100 : 0;

        $names = [
            self::WHITE     => 'WHITE',
            self::UNUSED    => 'UNUSED',
            self::ALL_WRONG => 'ALL_WRONG',
            self::GREEN     => 'GREEN',
            self::YELLOW    => 'YELLOW',
            self::BLACK     => 'BLACK',
        ];

        echo "=== " . basename($file) . " ===\n";
        echo "Filesize: " . number_format($filesize) . " bytes\n";
        echo "Total pixels: " . number_format($total) . "\n";
        echo "Unique colors: $unique (max: " . self::MAX_COLORS . ")\n";
        echo "Other pixels: " . number_format($otherSum) . " (" . number_format($otherPct, 3) . "%) — ";
        echo $otherPct <= self::MAX_OTHER_PERCENT ? "PASS" : "FAIL";
        echo "\n\n";

        foreach ($namedColors as $rgb) {
            $px = $counts[$rgb] ?? 0;
            $pct = $total > 0 ? ($px / $total) * 100 : 0;
            $hex = sprintf("#%06X", $rgb);
            $name = $names[$rgb];
            echo "  $hex ($name): " . number_format($px) . " px (" . number_format($pct, 3) . "%)\n";
        }

        echo "\n";
        echo "  OTHER: " . number_format($otherSum) . " px (" . number_format($otherPct, 3) . "%)\n";
        echo str_repeat("=", 60) . "\n\n";
    }
}

// ——— ANALYSIS: /tmp/w/Wordle/*.png ———
$srcp = '/tmp/w/Wordle/*.png';
$files = glob($srcp);

if (!$files) {
    echo "No files in /tmp/w/Wordle/\n";
    exit;
}

echo "Analyzing " . count($files) . " known Wordle screenshots...\n\n";

foreach ($files as $file) {
    WordleColorPalette::debug($file);

    // "Move" back to same folder (safe)
    $target = dirname($file) . '/' . basename($file);
    if ($file !== $target) {
        rename($file, $target);
    }
}