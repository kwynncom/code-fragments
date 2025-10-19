<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/var/kwynn/hours/PRIVATE_config.php');
require_once('db.php');

// header('Content-Type: application/json');
header('Content-Type: text/plain');


class postReceiveCl {

    public function __construct() {

	try {
	    $dat = $this->do10();
	    echo('dat');
	    odsDBCl::put($dat);
	    
	} catch (Throwable $ex) {
	    $this->htCrash($ex);
	}
    }

    private function do10() : array {

	$j = file_get_contents('php://input');
	kwas($j && is_string($j) && strlen($j) >= 10 && strlen($j) < 300, 'bad data err # 010821');
	$d = json_decode($j, true);
	kwas($d && is_array($d) && count($d) >= 1, 'bad data err # 010923');
	kwas(isset($d['secret']), 'secret not set err # 023328');
	$s =       $d['secret'];
	kwas(
		is_string($s)
		&& strlen($s) > 10
		, 'no valid secret err # 011025 at ' . date('r'));
	kwas ($s === KW_HOURS_PRIVATE_SECRET, 'incorrect secret err # 011127');
	unset($d['secret'], $s);

	return $d;
    }

    private function htCrash(Throwable $ex) {
	http_response_code(400);
	echo json_encode(['error' => $ex->getMessage(), 'status' => false]);
	exit(-1);
    }

}

if (isrv('post')) {
    echo('hi');
    new postReceiveCl();
}

/*
if (true) { // data validation
    $response = [
        'status' => 'OK',
    ];
    http_response_code(200);
} else {
    $response = [
        'status' => 'error',
        'message' => 'Missing required fields'
    ];
    http_response_code(400);
}

echo json_encode($response);
*/