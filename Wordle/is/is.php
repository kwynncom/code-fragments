<?php

declare(strict_types=1);

require_once('ranges.php');
require_once('/opt/kwynn/kwutils.php');

class WordleColorPalette implements WordleImageIntf
{

    public static function isWordleImage(string $file) : bool | string { 

	try {
	    return self::isWordleImageI($file);
	} catch(Throwable $ex) {
	    return $ex->getMessage();
	}

	return FALSE;
    }

    private static function validFileOrDie(string $file) : string {
        kwas(is_file($file) && is_readable($file), "$file not file or unreadable");
        $filesize = filesize($file);
        [$minFs, $maxFs] = WordleImageRangesF()['metrics']['filesize'];
        kwas($filesize > $minFs && $filesize < $maxFs, "$file size is bad");
	return $file;
    }

    private static function getImageDetails(string $filein) : array {

	$vfile = self::validFileOrDie($filein); 
        $img = imagecreatefrompng($vfile);
        kwas($img, 'image object bad');

	$w = imagesx($img); $h = imagesy($img);
	$totalPixels = $w * $h;
	[$minPx, $maxPx] = WordleImageRangesF()['metrics']['total_pixels'];
	kwas($totalPixels > $minPx && $totalPixels < $maxPx, "$filein pixels bad");

	unset($vfile, $minPx, $maxPx, $filein);

	$vars = get_defined_vars(); 
	return $vars;
    }

    private static function getImageTots(string $file) : array {

	$idat = self::getImageDetails($file); unset($file);
	extract($idat); unset($idat);

	$counts = [];
	for ($x = 0; $x < $w; $x++) {
	    for ($y = 0; $y < $h; $y++) {
		$rgb = imagecolorat($img, $x, $y) & 0xFFFFFF;
		$counts[$rgb] = ($counts[$rgb] ?? 0) + 1;
	    }
	} imagedestroy($img); unset($img, $x, $y, $w, $h, $rgb);

	self::pixPerOrDie($counts, $totalPixels);

	kwas(count($counts) < self::MAX_COLORS, ' has too many colors');

	return $counts;
	
    }

    private static function pixPerOrDie(array $counts, int $totalPixels) {
	$namedColors = [self::WHITE, self::UNUSED, self::ALL_WRONG, self::GREEN, self::YELLOW, self::BLACK];
	$namedSum = 0;
	foreach ($namedColors as $rgb) {
	    $namedSum += $counts[$rgb] ?? 0;
	}
	$otherSum = $totalPixels - $namedSum;
	$otherPct = $totalPixels > 0 ? ($otherSum / $totalPixels) * 100 : 0;
	kwas($otherPct < self::MAX_OTHER_PERCENT, 'bad pixel percentage');
    }

    private static function topCountsOrDie(array $counts) {

	arsort($counts);

	 $top = array_keys($counts);
	 kwas($top[0] === self::WHITE, 'bad white count');

	 $pos2to4 = array_slice($top, 1, 3);
	 $req = [self::GREEN, self::ALL_WRONG, self::UNUSED];
	 foreach ($req as $c) {
	     kwas(in_array($c, $pos2to4, true), 'bad top color counts');
	 }
    }

    private static function rangesOrDie(array $counts) : bool {
	 foreach (WordleImageRangesF()['colors'] as $rgb => [$min, $max]) {
	     $cnt = $counts[$rgb] ?? 0;
	     kwas($cnt > $min && $cnt < $max, 'bad count of ' . $rgb);
	 }

	 return true;
    }

    private static function countTestsOrDie(string $file) : bool {
	$counts = self::getImageTots($file); unset($file);
	self::topCountsOrDie($counts);
	return true;
    }

    public static function isWordleImageI(string $file) : bool | string
    {
	try {
	    if (self::countTestsOrDie($file) === true) return TRUE;
	} catch(Throwable $ex) {
	    return $file . ': ' . $ex->getMessage();
	} 

	kwas(false, 'should not get here (err # 0801107)');
	return false;
    }
}

// ——— SORTER: /tmp/w/Wordle/*.png → same folder ———
$srcp = '/tmp/w/*.png';
$dst  = '/tmp/w/Wordle';

$files = glob($srcp);
if (!$files) {
    echo "No files in /tmp/w/Wordle/\n";
    exit;
}

foreach ($files as $file) {
    $ok = WordleColorPalette::isWordleImage($file);
    $act = $ok === true ? 'MOVED' : 'SKIP ';

    if ($ok === true) {
        $target = $dst . '/' . basename($file);
        // rename($file, $target); // ()@@(@(@(@(@*$#!!!!
    }

    echo "[$act]: " . ($ok === true ? basename($file) : '') . ($ok !== true ? ' because ' . $ok : '') . PHP_EOL;
}