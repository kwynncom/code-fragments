<?php

require_once(__DIR__ . '/../gethw/mid.php');
require_once('config.php');

class machine_id_get {
    public static function get() {
	$raw = machine_id::get();
	$mid = machine_id_validity::get($raw);
	return $mid;
    }
}

if (didCLICallMe(__FILE__)) machine_id_get::get();
