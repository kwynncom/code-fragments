<?php
/**
 * Wordle top-5 colour analyser
 * -------------------------------------------------
 *  • Input  : any number of PNG files (all Wordle)
 *  • Output : wordle-stats.json
 *  • Config : $srcPattern   – glob of source files
 *              $marginPct   – % margin (e.g. 10 = ±10%)
 * -------------------------------------------------
 */

$srcPattern = '/tmp/w/Wordle/*.png';      // ← change to your folder / glob
$marginPct  = 10;                  // ±10 % of the observed min / max

$files = glob($srcPattern);
if (!$files) {
    echo "No files match $srcPattern\n";
    exit(1);
}

/* ------------------------------------------------------------------ */
/* 1. Gather pixel counts for the 5 Wordle colours across ALL images   */
/* ------------------------------------------------------------------ */
$wordleColours = [
    '#FFFFFF' => [],   // background
    '#D3D6DA' => [],   // empty tile
    '#787C7E' => [],   // wrong-spot (NYT gray)
    '#6AAA64' => [],   // correct
    '#C9B458' => [],   // original yellow (optional)
];

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

    // store only the 5 colours we care about
    foreach ($wordleColours as $hex => &$list) {
        $list[] = $cnt[$hex] ?? 0;
    }
}

/* ------------------------------------------------------------------ */
/* 2. For each colour compute min / max + margin                      */
/* ------------------------------------------------------------------ */
$stats = [];
foreach ($wordleColours as $hex => $values) {
    $min = min($values);
    $max = max($values);

    $margin = $marginPct / 100;
    $minMargin = (int)($min * (1 - $margin));
    $maxMargin = (int)($max * (1 + $margin));

    $stats[$hex] = [
        'observed_min' => $min,
        'observed_max' => $max,
        'margin_pct'   => $marginPct,
        'allowed_min'  => $minMargin,
        'allowed_max'  => $maxMargin,
    ];
}

/* ------------------------------------------------------------------ */
/* 3. Write JSON (pretty printed)                                      */
/* ------------------------------------------------------------------ */
$json = json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents('wordle-stats.json', $json);

echo "\nDone!  wordle-stats.json written.\n";
echo "Margin: ±$marginPct % of observed min / max\n\n";

/* ------------------------------------------------------------------ */
/* 4. Quick human-readable table                                      */
/* ------------------------------------------------------------------ */
echo str_pad("Colour", 10)
   . str_pad("Obs min", 12)
   . str_pad("Obs max", 12)
   . str_pad("±$marginPct% min", 14)
   . str_pad("±$marginPct% max", 14)
   . "\n";
echo str_repeat('-', 70) . "\n";

foreach ($stats as $hex => $s) {
    printf("%-10s %12d %12d %14d %14d\n",
        $hex,
        $s['observed_min'],
        $s['observed_max'],
        $s['allowed_min'],
        $s['allowed_max']
    );
}
