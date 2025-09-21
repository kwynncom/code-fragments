<?php

declare(strict_types=1);

class adbBattParseCl {

    private readonly array  $a;
    private readonly string $chargedBy;

    public function __construct(array $a) {
	$this->do10($a);
    }

    private function do10(array $a) {

	kwas(count($a) === 15, 'bad adb batt lines / count - err # 054412');
	$this->a = $a;
	$this->doCharge();

    }

    private function doCharge() {

	$ch = '';

	foreach(['AC', 'USB', 'Wireless'] as $shortKey => $source) {
	    $key = $source . ' ' . 'powered';
	    kwas(isset($this->a[$key]), 'key ' . $key . ' not found - err # 240550');
	    $vs =       $this->a[$key];
	    $v  = $vs === 'true' ? true : false;
	    if ($v === false) kwas($vs === 'false', 'bad true false charge value - err # 300558');
	    if (!$v) continue;
	    if ($ch) kwas(false, 'more than one charging method - err # 055432');
	    $ch = $shortKey;
	    continue;
	}

	$this->chargedBy = $ch ? $ch : 'disch';
    }
    
}

