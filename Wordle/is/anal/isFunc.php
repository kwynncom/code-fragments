<?php
// ================================================
// WORDLE SORTER – FINAL, BULLETPROOF
// Accepts 1–6 guess games, even with UI artifacts
// ================================================

$srcp = '/tmp/w/*.png';           // ← CHANGE ME
$dst  = '/tmp/w/Wordle';          // ← CHANGE ME

$src = glob($srcp);
if (empty($src)) { echo "No files.\n"; exit; }
if (!is_dir($dst)) mkdir($dst, 0755, true);

foreach ($src as $file) {
    $isWordle = isWordleImage($file);
    $action   = $isWordle ? 'MOVED' : 'SKIP ';
    $label    = $isWordle ? 'Wordle' : 'Other ';

    if ($isWordle) {
        $target = $dst . '/' . basename($file);
        rename($file, $target);
    }

    echo "[$action] $label: " . basename($file) . PHP_EOL;
}

function isWordleImage(string $file): bool
{
    if (!extension_loaded('gd')) return false;
    $img = @imagecreatefrompng($file);
    if (!$img) return false;

    $w = imagesx($img); $h = imagesy($img);
    $colors = [];

    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8)  & 0xFF;
            $b = $rgb & 0xFF;
            $hex = sprintf("#%02X%02X%02X", $r, $g, $b);
            $colors[$hex] = ($colors[$hex] ?? 0) + 1;
        }
    }
    imagedestroy($img);
    arsort($colors);
    $top6 = array_keys(array_slice($colors, 0, 6));

    // ——— 1. TOP 5 MUST BE WORDLE COLORS (IN FLEX ORDER) ———
    $orderMap = [
        0 => '#FFFFFF',
        1 => ['#D3D6DA', '#787C7E', '#6AAA64'],
        2 => ['#787C7E', '#D3D6DA', '#6AAA64'],
        3 => ['#6AAA64', '#787C7E', '#D3D6DA'],
        4 => ['#C9B458', '#6AAA64', '#787C7E', '#000000'],
        // 5 => ANYTHING (UI, keyboard, etc.)
    ];

    for ($i = 0; $i < 5; $i++) {
        $allowed = $orderMap[$i] ?? [];
        if (!in_array($top6[$i] ?? '', (array)$allowed)) {
            return false;
        }
    }

    // ——— 2. MIN PIXELS (RELAXED) ———
    $min = [
        '#FFFFFF' => 200000,
        '#D3D6DA'  => 30000,
        '#6AAA64'  => 40000,
        '#787C7E'  => 30000,
    ];

    foreach ($min as $hex => $threshold) {
        if (($colors[$hex] ?? 0) < $threshold) {
            return false;
        }
    }

    // ——— 3. EARLY WIN: GREEN DOMINATES ———
    $total = array_sum($colors);
    $green = $colors['#6AAA64'] ?? 0;
    $empty = $colors['#D3D6DA'] ?? 0;

    if ($green > 50000 && $empty < 90000) {
        return true;  // 3-guess or better
    }

    // ——— 4. BOARD COVERAGE ———
    $board = ($colors['#D3D6DA'] ?? 0) + ($colors['#787C7E'] ?? 0) + $green + ($colors['#C9B458'] ?? 0);
    return $board / $total >= 0.20;
}
