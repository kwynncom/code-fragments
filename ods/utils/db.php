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
	    $ret[$p] = $this->c->findOne(['project' => $p, 'active' => ['$ne' => false]], ['sort' => ['Ufile' => -1]]);
	}

	return $ret;
    }

    private function getProjects() : array {
	if (!$this->isup) return [];
	$res = $this->p->find(['active' => ['$ne' => false]]);
	if (!$res) return [];

	$ps = [];
	foreach($res as $r) {
	    $ps[] = $r['project'];
	}

	return $ps;

    }

    public function putI(array $a, bool $dec) {

	$this->vala = odsArrValCl::getValidAProj($a); unset($a);

	if ($this->isup) {
	    foreach($this->vala as $proj => $a) {
		$res = $this->putI20($proj);
		if ($res === 2) {
		    if (isrv('post')) echo($proj . 'OKDB_DUPLICATE');
		    continue;
		}
	    }
	}

	$this->doPost();
    }

    private function already() {
	
    }

    private function doPost() {
	if (isrv('post')) return;
	if ($this->already()) return;
	$pmac = hoursPostCl::statusKey();

	$topost = [];
	if ($this->isup) {
	    foreach($this->vala as $proj => $a) {
	       $q = [   'project' => $a['project'], 
			'Ufile' => $a['Ufile'],
			'posted.' . $pmac => ['$exists' => true]
		    ];

		if ($this->c->count($q) >= 1) continue;

		$topost[$proj] = $a;

	    }
	} else $topost = $this->vala;

	if (!$topost) return;
	$pok = hoursPostCl::post($topost);
	if (!$pok || !$this->isup) return;


	foreach($pok as $a) {

	   $q = [   'project' => $a['project'], 
		    'Ufile' => $a['Ufile']
		    ];
	    $dat = ['posted' => $a['posted']];
	    $upres84 = $this->c->upsert($q, $dat);
	    continue;
	}


	
    }

    private function tm() : bool {
	return (ispkwd() && time() < strtotime('2024-10-19 05:30'));
    }

    private readonly bool $isup;

    private function setDBStatus() {
	$t = false;
	try { 
	    $t = ($this->client->admin->command(['ping' => 1])->toArray()[0]['ok'] ?? 0) == 1; // == not ===
	} catch(Throwable $ex) { }
	$this->isup = $t;
	return;
    }

    private function initDB() {
	if (isset($this->c)) return;
	parent::__construct(self::dbname);
	$this->setDBStatus();
	
	if (!$this->isup) return;

	$this->c = $this->kwsel(self::coname);
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
	if ($this->p->count($qp) === 0) {
	    $upres38 = $this->p->upsert($qp, $qp);
	}
	$q = $qp;
	$q['Ufile'] = $this->vala[$proj]['Ufile'];

	$_id =  $this->vala[$proj]['project'] . '-' . 
		date('md-Hi-Y-s', $this->vala[$proj]['Ufile']) . 
		'-at-' . date('is') . '-'. base62(3);
	$dat = $this->vala[$proj];
	$dat['_id'] = $_id;

	if ($this->c->count($q) >= 1) {    return 2; 	}
	$inres55 = $this->c->insertOne($dat);
	if ($inres55->getInsertedCount() === 1) {
	    if (isrv('post')) echo("\n" . $proj . 'OKDB_INSERTED' . "\n");
	}

    } // func
} // class
