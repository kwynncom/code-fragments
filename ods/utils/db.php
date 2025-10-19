<?php

declare(strict_types=1);

require_once('post.php');

class odsDBCl extends dao_generic_4 {

    const dbname = 'hours';
    const coname = 'hours';
    const co20name = 'projects';

    private readonly array  $vala;
    private readonly object $c;
    private readonly object $p;

    public static function put(array $a) {
	try {  $o = new self();
	       $o->putI($a);
	       unset($a);	
	} 
	catch(Throwable $ex) {  if (iscli()) echo($ex->getMessage());}
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

    public function putI(array $a) {
	$this->vala = odsArrValCl::getValidAProj($a); unset($a);
	foreach($this->vala as $proj => $a) $this->putI20($proj);
    }

    private function initDB() {
	if (isset($this->c)) return;
	parent::__construct(self::dbname);
	$this->c = $this->kwsel(self::coname);
	$this->c->createIndex(['project' => 1, 'Ufile' => -1], ['unique' => true]);

	$this->p = $this->kwsel(self::co20name);
	$this->p->createIndex(['project' => 1], ['unique' => true]);
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

	hoursPostCl::post($dat);

	if ($this->c->count($q) >= 1) {    return; 	}
	$inres55 = $this->c->insertOne($dat);
	return;
    } // func
} // class
