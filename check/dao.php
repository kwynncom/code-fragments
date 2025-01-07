<?php

require_once('/opt/kwynn/kwutils.php');

class actCheckBegin2024DAOCl {
    
    const dbname = 'actCheckBegin2024';
    private readonly object $dato;
    private readonly string $f;
    

    public static function put(object $dato) {
	new self($dato);
    }

    private function __construct(object $dato) {
	$this->dato = $dato;
	$this->tmpFilePut();
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
	file_put_contents($this->f, json_encode($this->dato, JSON_PRETTY_PRINT));
    }
}


