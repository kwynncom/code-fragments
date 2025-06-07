<?php

require_once('/opt/kwynn/kwutils.php');

class xactsGetCl {
    
    const pyCmd = 'python3 ' . __DIR__ . '/../pygnucash/main.py';

    public readonly float $balStart;
    public readonly array $currXacts;

    public function __construct() {
	$this->do10();
    }

    private function do10() {
	$t = $this->getRaw();
	$this->do20A($t);
	
    }

    private function do20A(string $t) {

	$afwd = json_decode($t, true); unset($t);
	$a = array_reverse($afwd); unset($afwd);

	kwas($a && is_array($a), 'JSON did not yield initial array err# 063933');

	$startBal = 0;

	foreach($a as $i => $r) {
	    if ($r['reconciled'] === 'y') {
		$startBal = $r['bal'];
		break;
	    }
	} unset($r);

	if (!is_float($startBal)) $startBal = floatval($startBal);

	$a = array_slice($a, 0, $i + 1); unset($i);
	
	$this->balStart = $startBal; unset($startBal);
	$this->currXacts = $a;

	return;

    }

    private function getRaw() : string {
	 $t = shell_exec(self::pyCmd);
	 kwas($t && is_string($t) && strlen($t) > 10, 'GNU account file too small - err # 062919');
	 return $t;
    }
}
