<?php

// after this, define black.  Black and be greater than yellow.  

/**
 * Wordle Top-5 Colour Ranker
 * -------------------------------------------------
 *  • Input  : glob of PNG files (all known Wordle)
 *  • Output : console table + optional CSV
 * -------------------------------------------------
 */

$srcPattern = '/tmp/w/Wordle/*.png';   // ← change to your folder / glob
$writeCsv   = false;            // set true to write wordle-rankings.csv

$files = glob($srcPattern);
if (!$files) {
    echo "No files match $srcPattern\n";
    exit(1);
}

/* --------------------------------------------------------------- */
/*  Wordle colour constants (same as the class you already have)   */
/* --------------------------------------------------------------- */
define('WHITE',     '#FFFFFF');
define('UNUSED',    '#D3D6DA');
define('ALL_WRONG', '#787C7E');
define('GREEN',     '#6AAA64');
define('YELLOW',    '#C9B458');

$official = [WHITE, UNUSED, ALL_WRONG, GREEN, YELLOW];

/* --------------------------------------------------------------- */
$rows = [];   // will hold one entry per file

foreach ($files as $file) {
    $img = @imagecreatefrompng($file);
    if (!$img) { echo "skip (not PNG): $file\n"; continue; }

    $w = imagesx($img); $h = imagesy($img);
    $cnt = [];

    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8)  & 0xFF;
            $b = $rgb & 0xFF;
            $hex = sprintf("#%02X%02X%02X", $r, $g, $b);
            $cnt[$hex] = ($cnt[$hex] ?? 0) + 1;
        }
    }
    imagedestroy($img);

    // sort descending
    arsort($cnt);
    $top = array_slice($cnt, 0, 5, true);   // keep keys

    // build ranking for the 5 official colours
    $rank = [];
    foreach ($official as $c) {
        $rank[$c] = array_search($c, array_keys($top), true);
        $rank[$c] = $rank[$c] === false ? '—' : ($rank[$c] + 1);
    }

    // any non-official colour that made top-5?
    $intruders = [];
    foreach ($top as $hex => $pixels) {
        if (!in_array($hex, $official, true)) {
            $intruders[] = "$hex ($pixels px)";
        }
    }

    $rows[] = [
        'file'      => basename($file),
        'WHITE'     => $rank[WHITE],
        'UNUSED'    => $rank[UNUSED],
        'ALL_WRONG' => $rank[ALL_WRONG],
        'GREEN'     => $rank[GREEN],
        'YELLOW'    => $rank[YELLOW],
        'intruders' => $intruders ? implode(', ', $intruders) : '—',
    ];
}

/* --------------------------------------------------------------- */
/*  Print a nice table                                              */
/* --------------------------------------------------------------- */
$header = ['File', 'WHITE', 'UNUSED', 'ALL_WRONG', 'GREEN', 'YELLOW', 'Intruders'];
$widths = array_map('strlen', $header);

foreach ($rows as $r) {
    foreach ($r as $k => $v) {
        $w = strlen((string)$v);
        if ($w > $widths[array_search($k, array_keys($r))]) {
            $widths[array_search($k, array_keys($r))] = $w;
        }
    }
}

// header
echo str_pad('', array_sum($widths) + count($widths) * 3 - 1, '-') . "\n";
echo '|';
foreach ($header as $i => $h) {
    echo ' ' . str_pad($h, $widths[$i], ' ', STR_PAD_BOTH) . ' |';
}
echo "\n" . str_pad('', array_sum($widths) + count($widths) * 3 - 1, '-') . "\n";

// rows
foreach ($rows as $r) {
    echo '|';
    foreach ($r as $i => $v) {
        $pad = ($i === 'file') ? STR_PAD_RIGHT : STR_PAD_LEFT;
        echo ' ' . str_pad($v, $widths[array_search($i, array_keys($r))], ' ', $pad) . ' |';
    }
    echo "\n";
}
echo str_pad('', array_sum($widths) + count($widths) * 3 - 1, '-') . "\n";

/* --------------------------------------------------------------- */
/*  Optional CSV export                                            */
/* --------------------------------------------------------------- */
if ($writeCsv) {
    $fp = fopen('wordle-rankings.csv', 'w');
    fputcsv($fp, array_keys($rows[0]));
    foreach ($rows as $r) {
        fputcsv($fp, $r);
    }
    fclose($fp);
    echo "CSV written to wordle-rankings.csv\n";
}
