<?php

require_once('fileGet.php');
require_once('/var/kwynn/gnucash/privateInfo.php');
require_once(__DIR__ . '/www/template10.php');

class balancesCl implements balancesPrivateIntf {

    public  readonly array $calcs;

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
	echo($this->hto->getHTML($this->calcs));     
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
		    $penda[$r['splitGUID']] = $r;
		}
	    }

	    unset($amt, $isPast, $recon, $isPos);
	} unset($thea, $r);

	$this->penda = $penda; unset($penda);
	$this->balEnd = $balRunning;

	return get_defined_vars();
    }

    private function calcInEx() {
	$xa = [];
	$ba = [];
	$n  = count($this->penda);
	$np = pow(2, $n);

	$balEndCents = roint($this->balEnd * 100);

	for ($i=0; $i < $np; $i++) {
	    $tb = $balEndCents;
	    $cleara = [];
	    $j = 0;
	    foreach($this->penda as $xact) {
		$mask = $i & (1 << $j);
		$isClear = $mask ? false : true; unset($mask);
			$amtCents = roint($xact['amount'] * 100);
		if (!$isClear) $tb -= $amtCents; 
		else $cleara[] = $xact['splitGUID'];
		$j++;
	    } unset($j, $xact, $amtCents, $isClear);

	    $ba[] = $tb;

	    $xa[$tb][] = $cleara;

	    unset($cleara);
	    
	} unset($i, $np, $n, $tb, $balEndCents);

	$ba = array_unique($ba);
	rsort($ba);
	
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
	$remStBal = $balStart;
	if ($payments > 0.001) {
	    $showRem = true;
	    $remStBal -= $payments;
	}
	if ($showRem) 	$this->cec('rem st bal: ' . $this->nf($remStBal));
	else		$this->cec('starting ' . $this->nf($balStart));

	$avCr = $this->getAvCr($balRunning); unset($balRunning);
	$this->cec('avail cr *** ' . $this->nf($avCr) . ' ***');

	$this->cec('pend charges: ' . $this->nf($purchPending));

	$this->cec('starting ' . $this->nf($balStart));
	$this->cec('payments: ' . $this->nf($payments));
	$this->cec('completed purchases: ' . $this->nf($purchCompleted));

	unset($showRem);

	$minPayment = $this->getMinPayment($balStart);

	$this->calcs = get_defined_vars();

	return;
    }

    private function getMinPayment(float $bal) : float {
	// 31 days will be an overestimate for Feb, April, June, etc.
	// 0.01 to convert from 11% (yeah, right) APR to 0.11
	$interest = (31 / 365) * self::APR * 0.01 *  $bal;
	$more = $bal * 0.01; // min payment is interest plus fees plus 1%
	$min10 = $interest + $more;
	$ceil = ceil($min10);
	$min = $ceil;

	return $min;

    }

    private function getAvCr(float $bal) : float {
	return self::creditLimit - $bal;
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
	$this->hto = new acctTemplate10Cl(file_get_contents(self::bpidir . '/svg1.txt'));

    }

}

if (didCLICallMe(__FILE__)) new balancesCl();

