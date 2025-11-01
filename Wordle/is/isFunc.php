<?php

// $srcp = '/home/' . get_current_user() . '/Screenshots/*.png';
$srcp = '/tmp/w/*.png';
// $dst = '/home/' . get_current_user() . '/Screenshots/Wordle';
$dst = '/tmp/w/Wordle';

$src = glob($srcp);


foreach ($src as $file) {
    if (isWordleImage($file)) {
        rename($file, $dst . '/' . basename($file));
    }
}

function isWordleImage($file) {
    $img = @imagecreatefrompng($file);
    if (!$img) return false;

    $w = imagesx($img); $h = imagesy($img);
    $colors = [];
    for ($x=0; $x<$w; $x++) for ($y=0; $y<$h; $y++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8)  & 0xFF;
        $b = $rgb & 0xFF;
        $hex = sprintf("#%02X%02X%02X", $r, $g, $b);
        $colors[$hex] = ($colors[$hex] ?? 0) + 1;
    }
    imagedestroy($img);
    arsort($colors);

    $top = array_keys(array_slice($colors, 0, 6));

    $orderMap = [
        0 => '#FFFFFF',
        1 => ['#D3D6DA', '#787C7E'],
        2 => ['#787C7E', '#D3D6DA', '#6AAA64'],
        3 => ['#6AAA64', '#787C7E', '#D3D6DA'],
        4 => ['#C9B458', '#6AAA64', '#000000'],
        5 => ['#000000', '#C9B458']
    ];

    foreach ($orderMap as $pos => $allowed) {
        if (!in_array($top[$pos], (array)$allowed)) return false;
    }

    $ranges = [
        '#FFFFFF' => [304287, 353624],
        '#D3D6DA'  => [48489, 130087],
        '#787C7E'  => [43893, 178072],
        '#6AAA64'  => [49592, 113885],
        '#C9B458'  => [0, 33285],
        '#000000'  => [1298, 5680]
    ];

    foreach ($ranges as $hex => [$min, $max]) {
        $val = $colors[$hex] ?? 0;
        if ($val < $min*0.9 || $val > $max*1.1) return false;
    }

    return true;
}

