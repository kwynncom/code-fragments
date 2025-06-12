<?php

require_once('fileGet.php');
require_once('/var/kwynn/gnucash/privateInfo.php');
require_once(__DIR__ . '/www/template10.php');

class balancesCl implements balancesPrivateIntf {

    private readonly array $currx;
    private readonly int   $now;
    private readonly object $hto;
    private readonly bool   $htmlOnly;
    private readonly array $penda;
    private readonly float $balEnd;
   

    public function __construct(string $argsin = '') {
	$this->args($argsin);
	$this->init10();
	$this->calc10();
	$this->end();
    }

    private function end() { 
	if (!$this->htmlOnly) return;
	echo($this->hto->getHTML());     
    }

    public function args(string $conArgs) {
	if ($conArgs === 'html') { $this->htmlOnly = true; return; }
	global $argv;
	$this->htmlOnly = (kwifs($argv, 1) === 'html');
    }

    private function nf(float $f) : string { 	return '$' . number_format ($f, 2);     }

    private function calc20Loop() {
	
	$balStart = $this->currx[0]['bal'];
	$balRunning = $balStart;

	$thea = array_slice($this->currx, 1);

	$payments = 0;
	$purchCompleted = 0;
	$purchPending = 0;
	$penda = [];

	foreach($thea as $r) {

	    $this->hto->putLine($r);

	    $balRunning = $r['bal'];

	    $amt    = $r['amount'];
	    $isPast = $r['Uposted'] < $this->now;
	    $recon   = $r['reconciled'];
	    $isPos  = $amt > 0;

	    if ($isPast) {
	
		if ($recon === 'c') { 
		    if ($isPos) $purchCompleted += $amt;
		    else	$payments += $amt;
		}
		else {
		    $purchPending += $amt;
		    $penda[] = $r;
		}
	    }

	    unset($amt, $isPast, $recon, $isPos);
	} unset($thea, $r);

	$this->penda = $penda; unset($penda);
	$this->balEnd = $balRunning;

	return get_defined_vars();
    }

    private function calcInEx() {
	$ta = [];
	$n  = count($this->penda);
	$np = pow(2, $n);
	for ($i=0; $i < $np; $i++) {
	    $tb = $this->balEnd;
	    for($j=0; $j < $n; $j++) {
		$mask = $i & (2 << $j);
		if ($mask) $tb -= $this->penda[$j]['amount'];
		
	    }

	    $ta[] = $tb;
	}

	return;
    }

    private function calc10() {

	if (!$this->currx  || count($this->currx) < 2) return;

	if (!$this->htmlOnly) var_dump($this->currx);

	$vars = $this->calc20Loop();
	extract($vars); unset($vars);

	$this->calcInEx();

	$payments = -$payments;

	$balNaive = $balStart + $purchCompleted - $payments;


	$this->cec('naive balance '. $this->nf($balNaive));

	$showRem = false;
	if ($payments > 0.001) $showRem = true;
	if ($showRem) 	$this->cec('rem st bal: ' . $this->nf($balStart - $payments));
	else		$this->cec('starting ' . $this->nf($balStart));

	$this->cec('avail cr ' .  $this->getAvCr($balRunning));

	$this->cec('pend charges: ' . $this->nf($purchPending));

	$this->cec('starting ' . $this->nf($balStart));
	$this->cec('payments: ' . $this->nf($payments));
	$this->cec('completed purchases: ' . $this->nf($purchCompleted));

	unset($showRem);

	return;
    }

    private function getAvCr(float $bal) : string {
	return $this->nf(self::creditLimit - $bal);
    }

    private function cec(mixed $toOutput) {
	if (PHP_SAPI !== 'cli') return;
	if ($this->htmlOnly) return;
	echo($toOutput . "\n");
    }

    private function init10() {
	$this->now = time();
	$o = new xactsGetCl();
	$this->currx    = $o->currXacts;
	$this->hto = new acctTemplate10Cl();

    }

}

if (didCLICallMe(__FILE__)) new balancesCl();

