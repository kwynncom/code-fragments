<?php

class payConfCl {

    const tolInit = 0.01;

    private readonly array $calcs;

    public function __construct(array $calcs) {
	$this->calcs = $calcs; unset($calcs);
	$this->do10();
	
	return;
    }

    private function do10() {
	$ck = abs($this->calcs['payments'] - $this->calcs['minPaymentEst']) / $this->calcs['minPaymentEst'];
	if ($ck > self::tolInit) return; unset($ck);

	require_once('pcTempl10.php');
	return;
    }

}


new payConfCl($this->calcs);
