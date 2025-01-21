<?php

require_once('/opt/kwynn/kwutils.php');

class actCheckBegin2024DAOCl extends dao_generic_4 {
    
    const dbname = 'actCheckBegin2024';
    const actioncollnm = 'action';
    private readonly array $data;
    private readonly string $f;
    
    private readonly object $actionc;


    public static function put(array $data) {
	new self($data);
    }

    private function __construct(array $data) {
	parent::__construct(self::dbname);
	$this->actionc = $this->kwsel(self::actioncollnm);
	$this->data = $data;
	$this->tmpFilePut();
	$this->actionc->insertOne($data);
    }

    private function getfn() {
	$f = '/tmp/mysactckb24-';
	$f .= iscli() ? 'cli' : 'www';
	$f .= '.json';
	return $f;
    }

    private function tmpFilePut() {
	$this->f = $this->getfn();
	$res = file_put_contents($this->f, 'test');
	kwas($res === 4, 'cannot write to file 310351');
	kwas(chmod($this->f, 0600), 'cannot change file perms 310350');
	file_put_contents($this->f, json_encode($this->data, JSON_PRETTY_PRINT));
    }
}


