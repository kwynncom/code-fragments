<?php

declare(strict_types=1);

class adbBattParseCl {

    private readonly array $a;

    public function __construct(array $a) {
	$this->do10($a);
    }

    private function do10(array $a) {

	kwas(count($a) === 15, 'bad adb batt lines / count - err # 054412');
	$this->a = $a;
	$this->doCharge();

    }

    private function doCharge() {
	foreach(['AC', 'USB', 'Wireless'] as $source) {
	    $key = $source . ' ' . 'powered';
	    kwas(isset($this->a[$key]), 'key ' . $key . ' not found - err # 240550');
	    continue;
	}
    }
    
}

