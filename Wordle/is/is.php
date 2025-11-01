<?php

/**
 * WordleColorPalette – FINAL, INTEGER-KEYED, DATA-DRIVEN
 * -------------------------------------------------------
 * - Uses raw 24-bit RGB integers as keys (no sprintf)
 * - Colors: 6 Wordle colors
 * - Metrics: total_pixels, filesize
 * - Rules:
 *   1. WHITE = #1
 *   2. GREEN, ALL_WRONG, UNUSED in 2–4
 *   3. YELLOW, BLACK optional
 *   4. All in observed ±10% range
 */
final class WordleColorPalette
{
    // ——— MARGIN ———
    public const MARGIN_PERCENT = 10;

    // ——— COLORS (24-bit RGB integers) ———
    public const WHITE     = 0xFFFFFF; // #FFFFFF
    public const GREEN     = 0x6AAA64; // #6AAA64
    public const YELLOW    = 0xC9B458; // #C9B458
    public const ALL_WRONG = 0x787C7E; // #787C7E
    public const UNUSED    = 0xD3D6DA; // #D3D6DA
    public const BLACK     = 0x000000; // #000000

    // ——— OBSERVED COLOR LIMITS ———
    private const COLOR_LIMITS = [
        self::WHITE     => [219038, 355333],
        self::UNUSED    => [48489, 125903],
        self::ALL_WRONG => [43893, 178072],
        self::GREEN     => [49592, 113885],
        self::YELLOW    => [0,     33285],
        self::BLACK     => [1298,   5680],
    ];

    // ——— YOUR METRICS ———
    private const METRICS = [
        'total_pixels' => [520000, 650000],
        'filesize'     => [ 20000,  50000],
    ];

    // ——— COMPUTED RANGES (±10%) ———
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
        if (!extension_loaded('gd') || !is_file($file)) return false;

        $filesize = filesize($file);
        $img = @imagecreatefrompng($file);
        if (!$img) return false;

        $w = imagesx($img); $h = imagesy($img);
        $totalPixels = $w * $h;
        $counts = [];

        // ——— INTEGER-KEYED PIXEL LOOP (NO sprintf) ———
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y) & 0xFFFFFF; // mask alpha
                $counts[$rgb] = ($counts[$rgb] ?? 0) + 1;
            }
        }
        imagedestroy($img);

        // ——— 1. WHITE must be #1 ———
        arsort($counts);
        $top = array_keys($counts);
        if ($top[0] !== self::WHITE) return false;

        // ——— 2. GREEN, ALL_WRONG, UNUSED in 2–4 ———
        $pos2to4 = array_slice($top, 1, 3);
        $req = [self::GREEN, self::ALL_WRONG, self::UNUSED];
        foreach ($req as $c) {
            if (!in_array($c, $pos2to4, true)) return false;
        }

        // ——— 3. COLOR RANGES ———
        foreach (self::ranges()['colors'] as $rgb => [$min, $max]) {
            $cnt = $counts[$rgb] ?? 0;
            if ($cnt < $min || $cnt > $max) return false;
        }

        // ——— 4. METRICS ———
        [$minPx, $maxPx] = self::ranges()['metrics']['total_pixels'];
        if ($totalPixels < $minPx || $totalPixels > $maxPx) return false;

        [$minFs, $maxFs] = self::ranges()['metrics']['filesize'];
        if ($filesize < $minFs || $filesize > $maxFs) return false;

        return true;
    }

    // ——— PUBLIC: Debug (with hex names) ———
    public static function debug(string $file): void
    {
        if (!is_file($file)) { echo "Not file\n"; return; }

        $filesize = filesize($file);
        $img = @imagecreatefrompng($file);
        if (!$img) { echo "Not PNG\n"; return; }

        $w = imagesx($img); $h = imagesy($img);
        $total = $w * $h;
        $counts = [];
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y) & 0xFFFFFF;
                $counts[$rgb] = ($counts[$rgb] ?? 0) + 1;
            }
        }
        imagedestroy($img);
        arsort($counts);
        $top6 = array_slice($counts, 0, 6, true);

        $names = [
            self::WHITE     => 'WHITE',
            self::UNUSED    => 'UNUSED',
            self::ALL_WRONG => 'ALL_WRONG',
            self::GREEN     => 'GREEN',
            self::YELLOW    => 'YELLOW',
            self::BLACK     => 'BLACK',
        ];

        echo "=== $file ===\n";
        echo "Filesize: " . number_format($filesize) . " bytes\n";
        echo "Total pixels: " . number_format($total) . "\n";
        foreach ($top6 as $rgb => $px) {
            $hex = sprintf("#%06X", $rgb);
            $name = $names[$rgb] ?? 'OTHER';
            $rank = array_search($rgb, array_keys($top6)) + 1;
            echo "  #$rank $hex ($name): " . number_format($px) . " px\n";
        }
        echo "\n";
    }
}

// ——— SORTER ———
$srcp = '/tmp/w/*.png';
$dst  = '/tmp/w/Wordle';

$files = glob($srcp);
if (!$files) { echo "No files.\n"; exit; }
if (!is_dir($dst)) mkdir($dst, 0755, true);

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