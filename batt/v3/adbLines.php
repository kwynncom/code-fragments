<?php

declare(strict_types=1);

require_once('utils.php');
require_once('adbLevel.php');

class adbLinesCl {

    private readonly object $noti;

    public function __construct(?object $noti = null) {
	if ($noti) $this->noti = $noti;
    }

    public static function test() {
	$o = new self();
	$o->testI();
    }

    private function testI() {
	$f = '/tmp/a/1.log';
	kwas(is_readable($f), "no test file $f");
	$t = file_get_contents($f);
	$line = $this->findLastMatchingLine($t);
	belg($line);
    }

    private function findLastMatchingLine(string $s): ?string
    {
	$lines = preg_split('/\R/', $s);

	for ($i = count($lines) - 1; $i >= 0; $i--) {
	    $line = $lines[$i];
	    belg($line);
	    $t36 = self::checkLineTimestamp($line);
	    $d = $t36 === true ? 'n/a' : $t36;
	    belg((string)$d);
	    if ($ret = self::batteryLine($line) ?? false) { return $line; }
	    
	}

	return null;
    }

    private function batteryFilt05($s) : bool {
	// BatteryService: Processing new values:
	//  D BatteryService: Processing new values: 

	if (strpos($s, ' D BatteryService: Processing new values: ') === false) return false;
	else return true;
    }

    public function batteryLine(string $s) : ?int {
	// belg('att line match ' . $s);

	if (!self::batteryFilt05($s)) return null;

	preg_match('/level:(\d{1,3}),/', $s, $m);
	$t56 = self::batteryFilt10($m);
	if ($t56 ?? false) return $t56;

        
	preg_match('/ batteryLevel=(\d{1,3}),/', $s, $m);
	$t61 = self::batteryFilt10($m);
	if ($t61 ?? false) return $t61;

	return null;
    }	

    private function batteryFilt10($m) : ?int {
	// if ($m[0] ?? false) belg('match = ' . $m[0]);
	if (isset($m[1])) {
	    $tlev = adbLevelCl::filt($m[1]);
	    if ($tlev === false) return null;
	    if ($this->noti ?? false) {
		$noti->levelFromADBLog($tlev);
	    }
	    belg('bfilt10 ' . $tlev);
	    return $tlev;
	}

	return null;
    }

    private static function checkLineTimestamp(string $line): bool|float
    {
	if (preg_match('/^(\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{3})/', $line, $matches)) {
	    $timestampPart = $matches[1];
	    $currentYear = date('Y');
	    $fullDateStr = $currentYear . '-' . $timestampPart;
	    $dt = DateTime::createFromFormat('Y-m-d H:i:s.v', $fullDateStr);

	    if ($dt === false) {
		return true;
	    }

	    $now = new DateTime('now');
	    $diffInSeconds = $now->format('U.u') - $dt->format('U.u');

	    return (float) $diffInSeconds;
	}

	return true;
    }


}

if (didCLICallMe(__FILE__)) adbLinesCl::test();