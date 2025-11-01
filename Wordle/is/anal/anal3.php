<?php

/**
 * WordleColorPalette – Final, Rule-Based Validator
 * -------------------------------------------------
 * 1. WHITE must be #1
 * 2. GREEN, ALL_WRONG, UNUSED must be in positions 2–4
 * 3. YELLOW and BLACK optional
 * 4. Pixel counts within observed ±10%
 */
final class WordleColorPalette
{
    // ——— MARGIN ———
    public const MARGIN_PERCENT = 10;

    // ——— COLOR DEFINITIONS ———
    public const WHITE     = '#FFFFFF'; // Background
    public const GREEN     = '#6AAA64'; // Correct
    public const YELLOW    = '#C9B458'; // Original yellow
    public const ALL_WRONG = '#787C7E'; // NYT gray
    public const UNUSED    = '#D3D6DA'; // Empty tile
    public const BLACK     = '#000000'; // Letter text

    // ——— OBSERVED LIMITS (from your data) ———
    private const OBSERVED = [
        self::WHITE     => [219038, 355333],
        self::UNUSED    => [48489, 125903],
        self::ALL_WRONG => [43893, 178072],
        self::GREEN     => [49592, 113885],
        self::YELLOW    => [0,     33285],
        self::BLACK     => [1298,   5680],
    ];

    // ——— COMPUTED RANGES (± MARGIN_PERCENT) ———
    private static function getRanges(): array
    {
        static $computed = null;
        if ($computed !== null) return $computed;

        $margin = self::MARGIN_PERCENT / 100;
        $computed = [];

        foreach (self::OBSERVED as $color => [$min, $max]) {
            $computed[$color] = [
                (int)($min * (1 - $margin)),
                (int)($max * (1 + $margin))
            ];
        }

        return $computed;
    }

    // ——— PUBLIC: Validate image ———
    public static function isWordleImage(string $file): bool
    {
        if (!extension_loaded('gd')) return false;
        $img = @imagecreatefrompng($file);
        if (!$img) return false;

        $w = imagesx($img); $h = imagesy($img);
        $counts = [];

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8)  & 0xFF;
                $b = $rgb & 0xFF;
                $hex = sprintf("#%02X%02X%02X", $r, $g, $b);
                $counts[$hex] = ($counts[$hex] ?? 0) + 1;
            }
        }
        imagedestroy($img);

        // ——— 1. Sort by count ———
        arsort($counts);
        $top = array_keys($counts);

        // ——— 2. WHITE must be #1 ———
        if ($top[0] !== self::WHITE) {
            return false;
        }

        // ——— 3. GREEN, ALL_WRONG, UNUSED must be in 2–4 ———
        $positions_2_to_4 = array_slice($top, 1, 3);
        $required = [self::GREEN, self::ALL_WRONG, self::UNUSED];
        foreach ($required as $color) {
            if (!in_array($color, $positions_2_to_4, true)) {
                return false;
            }
        }

        // ——— 4. Pixel count ranges ———
        foreach (self::getRanges() as $color => [$min, $max]) {
            $count = $counts[$color] ?? 0;
            if ($count < $min || $count > $max) {
                return false;
            }
        }

        return true;
    }

    // ——— PUBLIC: Debug: Show top 6 + rankings ———
    public static function debugRankings(string $file): void
    {
        $img = @imagecreatefrompng($file);
        if (!$img) { echo "Not PNG\n"; return; }

        $counts = [];
        $w = imagesx($img); $h = imagesy($img);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($img, $x, $y);
                $hex = sprintf("#%02X%02X%02X", ($rgb>>16)&0xFF, ($rgb>>8)&0xFF, $rgb&0xFF);
                $counts[$hex] = ($counts[$hex] ?? 0) + 1;
            }
        }
        imagedestroy($img);
        arsort($counts);
        $top6 = array_slice($counts, 0, 6, true);

        echo "=== $file ===\n";
        foreach ($top6 as $hex => $px) {
            $name = array_search($hex, [
                self::WHITE, self::UNUSED, self::ALL_WRONG,
                self::GREEN, self::YELLOW, self::BLACK
            ]) ?: 'OTHER';
            $rank = array_search($hex, array_keys($top6)) + 1;
            echo "  #$rank $hex ($name): $px px\n";
        }
        echo "\n";
    }
}

// ——— SORTER EXAMPLE ———
$srcp = '/tmp/w/*.png';
$dst  = '/tmp/w/Wordle';

$files = glob($srcp);
if (!$files) { echo "No files.\n"; exit; }
if (!is_dir($dst)) mkdir($dst, 0755, true);

foreach ($files as $file) {
    $isWordle = WordleColorPalette::isWordleImage($file);
    $action   = $isWordle ? 'MOVED' : 'SKIP ';
    $label    = $isWordle ? 'Wordle' : 'Other ';

    if ($isWordle) {
        $target = $dst . '/' . basename($file);
        rename($file, $target);
    }

    echo "[$action] $label: " . basename($file) . PHP_EOL;
}