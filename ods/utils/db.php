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

    private function setValidA(array $a) {

	$ret = [];

	foreach($a as $proj => $a) {
	    kwas($proj && is_string($proj), 'bad project val (err # 071718 )');
	    $this->validWOrDie($proj);
	    $ret[$proj] = $this->getValidA20OrDie($a);
	}

	$this->vala = $ret;
    }

    private function getValidA20OrDie(array $a) : array {

	$ret = [];

	foreach($a as $k => $v) {
	    if (!(is_float($v) || is_integer($v) || $this->validWOrDie($v)))
		kwas(false, 'data val fail err # 073646');
	    $ret[$k] = $v;
	} unset($a, $k, $v);

	return $ret;
    }

    private function validWOrDie(string $s) : bool {
	kwas($s && trim($s) && strlen($s) <= 20, 'string fail (err # 071931 )');
	kwas(preg_match('/^[A-Za-z0-9]{1,20}$/', $s), 'string preg fail (err # 071831 )');
	return TRUE;
    }

}
