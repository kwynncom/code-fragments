<?php

declare(strict_types=1);

class odsDBCl extends dao_generic_4 {

    const dbname = 'hours';
    const coname = 'hours';


    private readonly array $vala;
    private readonly object $c;

    public static function put(array $a) {
	try { 
	    $o = new self($a); unset($a);
	} catch(Throwable $ex) {
	    if (iscli()) echo($ex->getMessage());
	}
    }

    private function __construct(array $a) {
	$this->setValidA($a); unset($a);
	$this->putI();
    }

    private function putI() {
	foreach($this->vala as $proj => $a) $this->putI20($proj);
    }

    private function initDB() {
	if (isset($this->c)) return;
	parent::__construct(self::dbname);
	$this->c = $this->kwsel(self::coname);
	$this->c->createIndex(['project' => 1, 'Ufile' => -1], ['unique' => true]);
    }

    private function putI20(string $proj) {
	$this->initDB();

	$q = ['project' => $proj, 'Ufile' => $this->vala[$proj]['Ufile']];
	if ($this->c->count($q) >= 1) return;

	$_id = $this->vala[$proj]['project'] . '-' . date('md-Hi-Y-s', $this->vala[$proj]['Ufile']);

	$dat = $this->vala[$proj];
	$dat['_id'] = $_id;

	$this->c->insertOne($dat);
		
	return;
    }



}
