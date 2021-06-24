<?php

require_once('serverDB.php');
receive();

function receive() {

	static $rkey = 'POSTob';

	try {
		kwas($_REQUEST[$rkey], "$rkey not found in POST/GET/REQUEST - receive() jsio test");
		$r = $_REQUEST[$rkey];
		$a = json_decode($r, true); kwas($a, "$rkey not json_decoded - receive() jsio test"); unset($r, $rkey);
	} catch(Exception $ex) {
		http_response_code(400); // 400 === bad request
		throw $ex;
	}

	$dao = new dao_jsio_example();
	$dao->put($a);
	
}
