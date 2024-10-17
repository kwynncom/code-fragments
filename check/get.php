<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once('/var/kwynn/mystery_2024_0920_1/params.php');

class mysckCl implements mysckpopa {

    const shouldDoIt = false;
    const maxRLen = 4500;
    const minRLen = 3000;
    
    private readonly object $dom;
    private readonly string $lval;
    private readonly int    $ridx;

    public function __construct() {
	$res = $this->get();
	$this->do10($res); unset($res);
	$this->do20();
	$this->do30();
    }

    private function do30() {
	$test = self::replies[$this->ridx] . date('F d') . '.';
	kwas($this->lval === $test, 'bad result 270335' );
    }

    private function do20() {
	$f = $this->dom->getElementById('en-result');
	$l = $f->getElementsByTagName('label')->item(0);
	kwas($l->getAttribute('for') === 'reply', 'bad match ele 230321');
	$this->lval = $l->nodeValue;
	foreach(self::replies as $i => $vv) {
	    $ss = substr($this->lval, 0, strlen($vv));
	    if ($ss === $vv) {
		$this->ridx = $i;
		return;
	    }
	}

	kwas(false, 'reply does not match 370327');
	return;
    }

    private function do10(string $t) {
	$o = new DOMDocument();
	libxml_use_internal_errors(true);
	$o->loadHTML($t); unset($t);
	libxml_clear_errors();	
	$this->dom = $o;
	return;

    }

    private function validrord($resin) : string {
	kwas($resin && is_string($resin), 'response fail 1100302');
	$l = strlen($resin);
	kwas($l >= self::minRLen && $l <= self::maxRLen, 'response fail 2140303');
	return $resin;
    }
    
    private function get() {
	if (self::shouldDoIt) return $this->getActual();
	$rand = random_int(0, 1);
	$tr = file_get_contents(self::tf[$rand]);
	return $this->validrord($tr);
	
    }

    private function getActual() : string {


	$ch = curl_init(mysckpopa::url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, mysckpopa::post);


	if (self::shouldDoIt) {
	    $response = curl_exec($ch);
	    curl_close($ch);
	    return $this->validord($response);
	}

	return 'check is turned off';

    }
}

if (didCLICallMe(__FILE__)) new mysckCl();