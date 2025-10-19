<?php

declare(strict_types=1);

require_once('post.php');
require_once('validate.php');

class odsDBCl extends dao_generic_4 {

    const dbname = 'hours';
    const coname = 'hours';
    const co20name = 'projects';

    private readonly array  $vala;
    private readonly object $c;
    private readonly object $p;

    public static function put(array $a, bool $doec = false) {
	try {  
	    if ($doec) echo('put 0356');
	    $o = new self();
	    $o->putI($a, $doec || isrv('post'));
	    unset($a);	
	    if ($doec) echo('lvput');
	} 
	catch(Throwable $ex) {  if ($doec || iscli() || $this->tm()) echo($ex->getMessage());}
    }

    public function __construct() {
	$this->initDB();
    }
 


    public function getLatest() : array {
	$ps = $this->getProjects();
	if (!$ps) return [];

	$ret = [];
	foreach($ps as $p) {
	    $ret[$p] = $this->c->findOne(['project' => $p], ['sort' => ['Ufile' => -1]]);
	}

	return $ret;
    }

    private function getProjects() : array {
	$res = $this->p->find([]);
	if (!$res) return [];

	$ps = [];
	foreach($res as $r) {
	    $ps[] = $r['project'];
	}

	return $ps;

    }

    public function putI(array $a, bool $dec) {
	if ($dec) {
	    echo('putI');
	    // var_dump($a);
	}
	$this->vala = odsArrValCl::getValidAProj($a); unset($a);
	if (!isrv('post')) hoursPostCl::post($this->vala);

	// if ($this->tm()) return;

	foreach($this->vala as $proj => $a) {
	    if ($dec) echo('pre20');
	    $res = $this->putI20($proj);
	    if ($dec) echo('post20');
	    if ($res === 2) {
		if ($dec) echo('dup');
		continue;
	    }
	}
    }

    private function tm() : bool {
	return (ispkwd() && time() < strtotime('2024-10-19 04:30'));
    }

    private function initDB() {
	if (isset($this->c)) return;
	parent::__construct(self::dbname);
	$this->c = $this->kwsel(self::coname);
	$this->c->createIndex(['project' => 1, 'Ufile' => -1], ['unique' => true]);

	$this->p = $this->kwsel(self::co20name);
	$this->p->createIndex(['project' => 1], ['unique' => true]);

	if ($this->tm()) {
	    echo('deleting');
	    $this->c->deleteMany([]);
	}

    }

    private function putI20(string $proj) {
	$this->initDB();
	$qp = ['project' => $proj];
	$upres38 = $this->p->upsert($qp, $qp);
	$q = $qp;
	$q['Ufile'] = $this->vala[$proj]['Ufile'];

	$_id = $this->vala[$proj]['project'] . '-' . date('md-Hi-Y-s', $this->vala[$proj]['Ufile']);
	$dat = $this->vala[$proj];
	$dat['_id'] = $_id;

	if ($this->c->count($q) >= 1) {    return 2; 	}
	$inres55 = $this->c->insertOne($dat);
	if ($inres55->getInsertedCount() === 1) {
	    echo("\n" . $proj . 'INSERTED_OK' . "\n");
	}

    } // func
} // class
