<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/validate.php');
require_once('ods.php');
require_once(__DIR__ . '/db.php');

class odsFirstSheetCl {

    const dperm = 30.416666667;
    
    public  readonly array $hours;
    public  readonly array $input;

    private readonly object $dbo;

    private static function getCalcsI(array $ain) : array {
	if (!$ain) return [];

	$input = $a = odsArrValCl::getValid($ain); unset($ain);

	$now = time();

	$Ustart = $a['Ustart'];
	$hoursWorked = $a['hours'];
	$s = $now - $Ustart;
	$hus = number_format($s); unset($hus);
	$elapM = $s / (self::dperm * DAY_S); unset($s);
	$permonth = $a['permonth'];
	$paid  = $elapM * $permonth; unset($elapM);
	$dph   = $paid /  $hoursWorked;
	$rate = $a['rate']; unset($a);
	$worked = $rate * $hoursWorked;
	$dolahead  = $worked - $paid; unset($worked, $paid);
	$hours = $dolahead / $rate;
	$targdpd = $permonth / self::dperm;
	$targhpd = $targdpd / $rate; unset($targhpd);
	$days = $dolahead / $targdpd; unset($dolahead, $targdpd);
	$earnedTo = roint($now + $days * DAY_S); unset($now);
	
	$vars = get_defined_vars(); 

	return $vars;
    }

    private function findMarker(array $a) : array {
	foreach($a as $i => $c) {
	    if (!$c) continue;
	    if (trim($c) !== odsArrValCl::marker) continue;
	    $ret = array_slice($a, $i);
	    return $ret;
	}
	return [];
    }

    private function getLatest() : array {
	$db = $this->dbo->getLatest();
	$f = odsDoCl::get($db);
	$ret = $this->findHighestUfilePerProject($db, $f);
	return $ret;
    }

// Function to find the highest Ufile for each project with all fields as siblings
// Function to find the highest Ufile for each project with all fields as siblings
function findHighestUfilePerProject($databases, $files) {
    $results = [];

    // Get all unique project names from both arrays, handling empty arrays
    $dbKeys = is_array($databases) ? array_keys($databases) : [];
    $fileKeys = is_array($files) ? array_keys($files) : [];
    $projects = array_unique(array_merge($dbKeys, $fileKeys));

    // Iterate through all projects
    foreach ($projects as $project) {
        // Initialize max Ufile and data for this project
        $maxUfile = 0;
        $maxData = [];

        // Check databases for this project
        if (isset($databases[$project]) && is_array($databases[$project])) {
	    
	    $entry = $databases[$project];
	    if (isset($entry['Ufile']) && $entry['Ufile'] > $maxUfile) {
		$maxUfile = $entry['Ufile'];
		$maxData = $entry;
	    }
            
        }

        // Check files for this project
        if (isset($files[$project]) && is_array($files[$project])) {
	    $entry = $files[$project];
	    if (isset($entry['Ufile']) && $entry['Ufile'] > $maxUfile) {
		$maxUfile = $entry['Ufile'];
		$maxData = $entry;
	    }
	}
        

        // Store result for this project if it exists
        if (!empty($maxData)) {
            $results[$project] = $maxData;
        }
    }

    return $results;
}




    public function __construct() {
	$this->dbo = new odsDBCl();
	$aa = $this->getLatest();
        $this->do10($aa);
        $this->toDB();
    }

    private function toDB() {
	odsDBCl::put($this->input);
    }

    private function do10(array $aa) {

	$ret = [];
	$db  = [];

	foreach($aa as $a) {
	    $t = $this->parse30($a);
	    if (!$t) continue;
	    $proj = $t['calcs']['project'];
	    $ret[$proj] = $t['calcs']; unset($t['calcs']);
	    $db [$proj] = kwam($t['uq'], $t['input']);
	}

	$this->hours = $ret;
	$this->input = $db;

	return;
    }

    private function parse30(array $aall) : array {
	$a = $this->findMarker($aall['all']);
	$dat = $this->getCalcsI($a);
	if (!$dat) return [];
	$uq = [];
	$uq['project'] = $aall['project'];
	$uq['Ufile'  ] = $aall['Ufile'];
	$input = $dat['input']; 
	unset   ($dat['input']);
	$ret = kwam($dat, $uq);
	return ['uq' => $uq, 'calcs' => $ret, 'input' => $input];
    }
 
}
