<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/mongodb2.php');

class tick_time_study extends dao_generic_2 {
    const dbName = 'tick';

    public function __construct($exec = false) {
	parent::__construct(self::dbName, __FILE__);
	$this->creTabs(['t' => 'tick']);
	if ($exec) {
	    $this->execSelf();
	    $this->doit();
	}
    }
    
    private function execSelf() {
	global $argv;
	
	if (!isset($argv[1])) return;
	if (	   $argv[1] !== 'init') return;
	
	$cmd = 'nohup php ' . __FILE__ . ' 2> /dev/null &';
	exec($cmd);
	exit(0);
    }
    
    private function doit() {
	$d1 = $this->tcoll->getSeq2(true, false, true);
	$d2 = nanopk();
	$dat = array_merge($d1, $d2);
	$this->tcoll->insertOne($dat);	
    }
}

if (didCLICallMe(__FILE__)) new tick_time_study(1);