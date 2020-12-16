<?php

require_once(__DIR__ . '/../gethw/mid.php');
require_once('config.php');

class machine_id_get {
    public static function get() {
	$raw = machine_id::get();
	machine_id_validity::get($raw);
	return;
    }
}

if (didCLICallMe(__FILE__)) machine_id_get::get();
