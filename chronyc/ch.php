<?php

require_once('/opt/kwynn/mongodb2.php');
// require_once('utils/stableTicks.php');
require_once('hu1.php');

class chrony extends dao_generic_2 {
    
    const dbName = 'ticks';
    const datv  = 5;

    public function __construct($fromChild = false) {
	parent::__construct(self::dbName, __FILE__);
	$this->creTabs(['c' => 'chrony', 't' => 'ticks']);
	$this->getI10();
	$this->d10();
	
    }
    
    private function getI10() {
	$b  = nanotime();
	$ch = $this->getI20();
	$e  = nanotime();
	$this->p30(get_defined_vars());
    }
    
    private function p30($dat) {
	$dat['datv'] = self::datv;
	$dat['_id']  = $this->ccoll->getSeq2('idoas');
	$this->ccoll->insertOne($dat);
	$this->chraw = $dat['ch'];
	return;
    }
    
    private function getI20() {
	global $argv;
	global $argc;

	$argv[0] = '/usr/bin/chronyc';
	if ($argc === 1) $argv[1] = 'tracking';
	$cmd = implode(' ', $argv);
	$res = shell_exec($cmd);
	return $res;
    }
    
    public static function get() { $o = new self();    }
    
    private function d10() {
	// $rs = $this->ccoll->find([], ['sort' => ['b' => -1], 'limit' => 40]);
	chrony_parse::parse($this->chraw /*$rs*/);
	return;
    }
}


if (didCLICallMe(__FILE__)) chrony::get();
