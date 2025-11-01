<?php

/**
 * WordleColorPalette – FINAL, MAX_COLORS AFTER LOOP
 * -------------------------------------------------
 * - $namedColors declared when needed
 * - MAX_COLORS = 1100 checked AFTER loop
 * - filesize + total_pixels = early filter
 * - Other ≤ 1.6%
 * - Integer-keyed
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

        // ——— 3. LOOP: COUNT ALL COLORS ———
        $counts = [];
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y) & 0xFFFFFF;
                $counts[$rgb] = ($counts[$rgb] ?? 0) + 1;
            }
        }
        imagedestroy($img);

        // ——— 4. MAX_COLORS (after loop) ———
        if (count($counts) > self::MAX_COLORS) {
            return false;
        }

        // ——— 5. OTHER% ———
        $namedColors = [self::WHITE, self::UNUSED, self::ALL_WRONG, self::GREEN, self::YELLOW, self::BLACK];
        $namedSum = 0;
        foreach ($namedColors as $rgb) {
            $namedSum += $counts[$rgb] ?? 0;
        }
        $otherSum = $totalPixels - $namedSum;
        $otherPct = $totalPixels > 0 ? ($otherSum / $totalPixels) * 100 : 0;
        if ($otherPct > self::MAX_OTHER_PERCENT) {
            return false;
        }

        // ——— 6. WHITE = #1 ———
        arsort($counts);
        $top = array_keys($counts);
        if ($top[0] !== self::WHITE) return false;

        // ——— 7. GREEN, ALL_WRONG, UNUSED in 2–4 ———
        $pos2to4 = array_slice($top, 1, 3);
        $req = [self::GREEN, self::ALL_WRONG, self::UNUSED];
        foreach ($req as $c) {
            if (!in_array($c, $pos2to4, true)) return false;
        }

        // ——— 8. COLOR RANGES ———
        foreach (self::ranges()['colors'] as $rgb => [$min, $max]) {
            $cnt = $counts[$rgb] ?? 0;
            if ($cnt < $min || $cnt > $max) return false;
        }

        return true;
    }
}

// ——— SORTER: /tmp/w/Wordle/*.png → same folder ———
$srcp = '/tmp/w/Wordle/*.png';
$dst  = '/tmp/w/Wordle';

$files = glob($srcp);
if (!$files) {
    echo "No files in /tmp/w/Wordle/\n";
    exit;
}

foreach ($files as $file) {
    $ok = WordleColorPalette::isWordleImage($file);
    $act = $ok ? 'MOVED' : 'SKIP ';
    $lbl = $ok ? 'Wordle' : 'Other ';

    if ($ok) {
        $target = $dst . '/' . basename($file);
        rename($file, $target);
    }

    echo "[$act] $lbl: " . basename($file) . PHP_EOL;
}