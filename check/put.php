<?php

require_once('/opt/kwynn/kwutils.php');


class dailyCheckDao extends dao_generic_4 {

    const dbname = 'dailyCheckMys2024';
    const rawcollnm = 'raw';

    private readonly object $rawc;

    private function __construct() {
	parent::__construct(self::dbname);
	$this->rawc = $this->kwsel(self::rawcollnm);

    }

}