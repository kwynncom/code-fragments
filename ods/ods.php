<?php

require_once('/opt/kwynn/kwutils.php');

class odsFirstSheetCl {
    const source = '/var/kwynn/hours/';

    public function __construct() {
	$this->do10();
    }

    private function do10() {
	$fs = glob(self::source . '*.ods');
	foreach($fs as $f) $this->do20($f);
	return;
    }

    private function already(string $f) : bool {
	$csv = str_replace('.ods', '.csv', $f);
	if (filemtime($csv) >= filemtime($f)) return true;
	else return false;
    }

    private function do20(string $f) {

	if ($this->already($f)) return;

	//    soffice --headless --convert-to csv blah.ods --outdir .
	$c = 'soffice --headless --convert-to csv ' . $f . ' --outdir ' . self::source;
	$res = shell_exec($c);

// "convert /var/kwynn/hours/blah.ods as a Calc document -> /var/kwynn/hours/blah.csv using 
// filter : Text - txt - csv (StarCalc) "

	return;

    }
}

if (didCLICallMe(__FILE__)) new odsFirstSheetCl();



