<?php
// ================================================
// Wordle Screenshot Sorter – Flexible Paths
// ================================================

// ——— EDIT THESE PATHS ———
$srcp = '/tmp/w/*.png';           // ← Source: change as needed
$dst  = '/tmp/w/Wordle';          // ← Destination folder

// ——— DO NOT EDIT BELOW THIS LINE ———
$src = glob($srcp);

if (empty($src)) {
    echo "No PNG files found in: $srcp\n";
    exit;
}

if (!is_dir($dst)) {
    mkdir($dst, 0755, true);
    echo "Created destination folder: $dst\n";
}

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

// ================================================
function isWordleImage(string $file): bool
{
    if (!extension_loaded('gd')) {
        echo "ERROR: GD extension not loaded.\n";
        return false;
    }

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

    // ——— 1. FLEXIBLE COLOR ORDER (HEATMAP) ———
    $orderMap = [
        0 => '#FFFFFF',
        1 => ['#D3D6DA', '#787C7E', '#6AAA64'],
        2 => ['#787C7E', '#D3D6DA', '#6AAA64'],
        3 => ['#6AAA64', '#787C7E', '#D3D6DA'],
        4 => ['#C9B458', '#6AAA64', '#787C7E', '#000000'],
        5 => ['#000000', '#C9B458', '#DCDEE1']
    ];

    foreach ($orderMap as $pos => $allowed) {
        if (!in_array($top6[$pos] ?? '', (array)$allowed)) {
            return false;
        }
    }

    // ——— 2. PIXEL COUNT RANGES (±15%) ———
    $ranges = [
        '#FFFFFF' => [219038, 355333],
        '#D3D6DA'  => [48489, 130087],
        '#787C7E'  => [43893, 178072],
        '#6AAA64'  => [49592, 113885],
        '#C9B458'  => [0, 33285],
        '#000000'  => [0, 5680],
    ];

    foreach ($ranges as $hex => [$min, $max]) {
        $val = $colors[$hex] ?? 0;
        if ($val < $min * 0.85 || $val > $max * 1.15) {
            return false;
        }
    }

    // ——— 3. BOARD COVERAGE CHECK ———
    $total = array_sum($colors);
    $board = ($colors['#D3D6DA'] ?? 0) + ($colors['#787C7E'] ?? 0) + ($colors['#6AAA64'] ?? 0) + ($colors['#C9B458'] ?? 0);
    if ($board / $total < 0.25) return false;

    return true;
}
?>