<?php

require_once('fileGet.php');
require_once('/var/kwynn/gnucash/privateInfo.php');
require_once(__DIR__ . '/www/template10.php');

class balancesCl implements balancesPrivateIntf {

    private readonly array $currx;
    private readonly int   $now;
    private readonly object $hto;
    public  readonly string $html;

    public function __construct() {
	$this->init10();
	$this->calc10();
    }

    private function nf(float $f) : string {
	return '$' . number_format ($f, 2);
    }

    private function calc10() {

	if (!$this->currx  || count($this->currx) < 2) return;

	var_dump($this->currx);

	$balStart = $this->currx[0]['bal'];
	$mis = 0;

	$thea = array_slice($this->currx, 1);
	// next($thea);
	$com = 0;
	$pay = 0;
	$purch = 0;
	$pendch = 0;

	foreach($thea as $r) {

	    $this->hto->putLine($r);

	    $amt    = $r['amount'];
	    $isPast = $r['Uposted'] < $this->now;
	    $rest   = $r['reconciled'];
	    $isPos  = $amt > 0;

	    if ($isPast) {
		if ($isPos  )     { $mis += $amt;  }
		else		    $pay += $amt;
		
		if ($rest === 'c') { $com += $amt; $purch += $amt; }
		else $pendch += $amt;
		continue;
	    }
	}

	// this becomes rem st bal if payment
	$this->cec('starting ' . $this->nf($balStart));
	$this->crlim($balStart + $mis);
	$this->cec('naive balance '. $this->nf($balStart + $com));
	$this->cec('completed purchases: ' . $this->nf($purch));
	$this->cec('pend charges: ' . $this->nf($pendch));

	$this->html = $this->hto->getHTML();
	echo($this->html);
    }

    private function crlim(float $bal) {
	$this->cec('avail credit ' . $this->nf(self::creditLimit - $bal));
    }

    private function cec(mixed $out) {
	if (PHP_SAPI !== 'cli') return;
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

