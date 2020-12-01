<?php

require_once('/opt/kwynn/kwcod.php');
require_once('/opt/kwynn/kwutils.php');

require_once(__DIR__ . '/../cpu/utils/dao.php');

class kwcoll2 extends kwcoll {
    public function __construct($mgr, $db, $coll, $tma, $tpid) {
	parent::__construct($mgr, $db, $coll, $tma);
	$this->tpid = $tpid;
    }
}


class kwmoncli2 extends kwmoncli {
    public function selectCollection2($db, $coll, $tableid) {
	return new kwcoll2($this->getManager(), $db, $coll, ['typeMap' => ['array' => 'array','document' => 'array', 'root' => 'array']], $tableid); 
	
    }    
}

class dao_generic_2 extends dao_generic {

    public function __construct($dbname) {
	$this->dbname = $dbname;
	$this->client = new kwmoncli2();
    }
    
    protected function creTabs($dbin, $ts) {
	foreach($ts as $k => $t) {
	    $v = $k . 'coll';
	    $this->$v = $this->client->selectCollection2($dbin, $t, $k);
	}	
    }
}


class dao_lav extends dao_generic_2 {
    
    const dbName = 'aws_cpu';
    
    public function __construct() {
	parent::__construct(self::dbName);
	$tsa = [
	    'l' => 'loadavg'	    ];
	$this->creTabs(self::dbName, $tsa);
    }
}

class dao_seq_kw2 extends dao_generic {
    
    const dbName = 'seqs';
    
    public function __construct() {
	parent::__construct(self::dbName);
	$c->createIndex(['db' => -1, 'name' => -1], ['unique' => true ]);
    }    
}

// $rarr['lav'] = sys_getloadavg();
new dao_lav();