<?php

require_once('fileGet.php');
require_once('/var/kwynn/gnucash/privateInfo.php');

class balancesCl implements balancesPrivateIntf {

    private readonly array $currx;
    private readonly int   $now;

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

	foreach($thea as $r) {
	    if ($r['Uposted'] < $this->now) {
		if ($r['amount'] > 0) $mis += $r['amount'];
		if ($r['reconciled'] === 'c') $com += $r['amount'];
		continue;
	    }
	}

	// this becomes rem st bal if payment
	$this->cec('starting ' . $this->nf($balStart));
	$this->crlim($balStart + $mis);
	$this->cec('naive balance '. $this->nf($balStart + $com));
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

    }

}

if (didCLICallMe(__FILE__)) new balancesCl();

