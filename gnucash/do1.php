<?php

require_once('fileGet.php');
require_once('/var/kwynn/gnucash/privateInfo.php');
require_once(__DIR__ . '/www/template10.php');

class balancesCl implements balancesPrivateIntf {

    private readonly array $currx;
    private readonly int   $now;
    private readonly object $hto;
    private readonly bool   $htmlOnly;
   

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

    private function calc10() {

	if (!$this->currx  || count($this->currx) < 2) return;

	if (!$this->htmlOnly) var_dump($this->currx);

	$balanceStart = $this->currx[0]['bal'];
	$mis = 0;

	$thea = array_slice($this->currx, 1);

	$completedAll = 0;
	$payments = 0;
	$completedPurch = 0;
	$pendingPurch = 0;
	$balanceRunning = $balanceStart;

	foreach($thea as $r) {

	    $this->hto->putLine($r);

	    $balanceRunning = $r['bal'];

	    $amt    = $r['amount'];
	    $isPast = $r['Uposted'] < $this->now;
	    $rest   = $r['reconciled'];
	    $isPos  = $amt > 0;

	    if ($isPast) {
		if ($isPos  )     { $mis += $amt;  }
		else		    $payments += $amt;
		
		if ($rest === 'c') { 
		    $completedAll += $amt; 
		    if ($isPos) $completedPurch += $amt; 
		}
		else $pendingPurch += $amt;
	    }

	    unset($amt, $isPast, $rest, $isPos);
	} unset($thea, $r);

	$payments = -$payments;

	$balanceNaive = $balanceStart + $completedAll;
	$this->cec('starting ' . $this->nf($balanceStart));
	$this->crlim($balanceRunning);
	$this->cec('naive balance '. $this->nf($balanceNaive));
	$this->cec('completed purchases: ' . $this->nf($completedPurch));
	$this->cec('pend charges: ' . $this->nf($pendingPurch));
	$this->cec('payments: ' . $this->nf($payments));
	$this->cec('rem st bal: ' . $this->nf($balanceStart - $payments));

    }

    private function crlim(float $balance) {
	$this->cec('avail credit ' . $this->nf(self::creditLimit - $balance));
    }

    private function cec(mixed $out) {
	if (PHP_SAPI !== 'cli') return;
	if ($this->htmlOnly) return;
	echo($out . "\n");
    }

    private function init10() {
	$this->now = time();
	$o = new xactsGetCl();
	$this->currx    = $o->currXacts;
	$this->hto = new acctTemplate10Cl();

    }

}

if (didCLICallMe(__FILE__)) new balancesCl();

